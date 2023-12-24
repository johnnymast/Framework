<?php

namespace App\Framework\Auth\Http\Controllers;

use App\Framework\Auth\Factory\UserFactory;
use App\Framework\Auth\Event\UserCreatedEvent;
use App\Framework\Auth\Exceptions\AuthRegisterException;
use App\Framework\Auth\Factory\OAuthCredentialFactory;
use App\Framework\Auth\Guard;
use App\Framework\Auth\Interfaces\RegisterUserInterface;
use App\Framework\Auth\Model\OAuthCredential;
use App\Framework\Auth\Repository\OAuthCredentialRepository;
use App\Framework\Events\Dispatcher;
use App\Framework\Events\Providers\Provider as DispatcherProvider;
use App\Framework\Session\Facade\Session;
use App\Framework\Validation\Exceptions\ValidationDefinitionException;
use App\Framework\Validation\Validator;
use App\Http\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Exception;
use League\OAuth2\Client\Provider\AbstractProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReflectionException;

use League\OAuth2\Client\Provider\Google;
use Wohali\OAuth2\Client\Provider\Discord;
use League\OAuth2\Client\Provider\Github;

class OAuthController extends Controller
{
    /**
     * @var string
     */
    protected string $redirectTo = 'home';

    /**
     * @var Guard
     */
    protected Guard $guard;

    /**
     * LoginController constructor.
     *
     * @param EntityManager $em Inject entity manager.
     */
    public function __construct(EntityManager $em)
    {
        $this->guard = new Guard();
        $settings = config('auth.login');

        if (isset($settings['redirect_to'])) {
            $this->redirectTo = $settings['redirect_to'];
        }

        parent::__construct($em);
    }

    private function loadProvider(string $service): ?AbstractProvider
    {
        $settings = config('auth.oauth');
        switch ($service) {
            case 'google':

                if ($settings['google']['enabled']) {
                    return new Google([
                        'clientId' => $settings['google']['client_id'],
                        'clientSecret' => $settings['google']['client_secret'],
                        'redirectUri' => url('auth/oauth/google/redirect'),
                    ]);
                }

                return null;

            case 'discord':

                if ($settings['discord']['enabled']) {
                    return new Discord([
                        'clientId' => $settings['discord']['client_id'],
                        'clientSecret' => $settings['discord']['client_secret'],
                        'redirectUri' => url('auth/oauth/discord/redirect'),
                    ]);
                }

                return null;

            case 'github':

                if ($settings['github']['enabled']) {
                    return new Github([
                        'clientId' => $settings['github']['client_id'],
                        'clientSecret' => $settings['github']['client_secret'],
                        'redirectUri' => url('auth/oauth/github/redirect'),
                    ]);
                }

                return null;
            default:
                return null;
        }
    }

    private function getScope(string $service): array
    {
        return match ($service) {
            'discord' => ['identify', 'email'],
            'github' => ['user', 'user:email', 'repo'],
            default => [],
        };
    }


    public function login(Request $request, Response $response, array $args = []): Response
    {
        $options = [];
        $provider = $this->loadProvider($args['service']);
        $options['scope'] = $this->getScope($args['service']);

        $authUrl = $provider->getAuthorizationUrl($options);

        // https://local.pinkhatdancers.site/auth/oauth/google/register
        Session::set('oauth2state', $provider->getState());

        return $response
            ->withStatus(302)
            ->withHeader('Location', $authUrl);
    }

    public function register(Request $request, Response $response, array $args = []): Response
    {
        $options = [];
        $provider = $this->loadProvider($args['service']);
        $options['scope'] = $this->getScope($args['service']);

        $authUrl = $provider->getAuthorizationUrl($options);

        // https://local.pinkhatdancers.site/auth/oauth/google/register
        Session::set('oauth2state', $provider->getState());

        return $response
            ->withStatus(302)
            ->withHeader('Location', $authUrl);
    }

    /**
     * @throws \App\Framework\Auth\Exceptions\AuthRegisterException
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function redirect(Request $request, Response $response, array $args = []): Response
    {
        $service = $args['service'] ?? '';
        $provider = $this->loadProvider($service);
        $state = $_GET['state'] ?? null;

        // TODO: 404 at unknown service
        // TODO: Check if credentials already exist for different provider.
        //
        $flash = Session::getFlash();

        if ($state !== Session::get('oauth2state')) {
            Session::delete('oauth2state');
            exit('Invalid state');
        }

        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Optional: Now you have a token you can look up a users profile data
        try {
            $settings = config('auth.user');
            $repository = app()->resolve(EntityManager::class)->getRepository($settings['entity']);
            $guard = new Guard();

            $oauthUser = $provider->getResourceOwner($token);
            $email = $oauthUser->getEmail();

            if (($user = $repository->findOneBy(['email' => $email]))) {
                try {
                    $repository = app()->resolve(EntityManager::class)->getRepository(OAuthCredential::class);
                    $credentials = $repository->findOneBy(['user' => $user->getId()]);

                    if (!$credentials) {
                        throw new AuthRegisterException("Account already exists.");
                    }

                    if ($credentials && $credentials->getService() !== $service) {
                        throw new AuthRegisterException("Account already exists.");
                    }

                } catch (AuthRegisterException $e) {
                    $flash->add('error', $e->getMessage());

                    return $response
                        ->withStatus(302)
                        ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor('auth.register'));
                }
            } else {
                $name = match ($service) {
                    'google' => $oauthUser->getName(),
                    'discord' => $oauthUser->getUsername(),
                    'github' => $oauthUser->getNickname(),
                    'default' => $oauthUser->getEmail(),
                };

                $user = UserFactory::create(
                    name: $name,
                    email: $email,
                    password: password_hash(rand(0, 1000) . '_' . time(), PASSWORD_DEFAULT),
                    no_confirmation: true,
                );

                $provider = new DispatcherProvider(app()->resolve(RegisterUserInterface::class));
                $dispatcher = new Dispatcher($provider);
                $dispatcher->dispatch(new UserCreatedEvent($user));

                OAuthCredentialFactory::create(
                    token: $token->getToken(),
                    service: $service,
                    expires: $token->getExpires(),
                    user: $user
                );
            }

            $guard->login($user);
            $flash->add('success', 'User Logged in');

            return $response
                ->withStatus(302)
                ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo));
        } catch (Exception $e) {
            return $response
                ->withStatus(302)
                ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor('auth.register'));
        }
    }
}
