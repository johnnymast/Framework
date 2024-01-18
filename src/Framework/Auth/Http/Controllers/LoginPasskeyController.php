<?php

namespace App\Framework\Auth\Http\Controllers;

use App\Framework\Auth\Factory\PasskeyCredentialFactory;
use App\Framework\Auth\Guard;
use App\Framework\Auth\Model\PasskeyCredential;
use App\Framework\Session\Facade\Session;
use App\Framework\Validation\Validator;
use App\Http\Controller\Controller;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Firehed\WebAuthn\ChallengeManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

use Firehed\WebAuthn\{Codecs, ArrayBufferResponseParser, CredentialContainer, RelyingPartyInterface};


class LoginPasskeyController extends Controller
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

//        $login_blocking = config('auth.login_blocking');

//        if ($login_blocking['enabled']) {
//            $this->enableLoginBlocking = true;
//            $this->maxLoginAttempts = $login_blocking['max_attempts'];
//            $this->loginTimeoutMinutes = $login_blocking['timeout_in_minutes'];
//        }

        parent::__construct($em);
    }

//    /**
//     * LoginController constructor.
//     *
//     * @param EntityManager $em Inject entity manager.
//     */
//    public function __construct(EntityManager $em)
//    {
//        $this->guard = new Guard();
//        $settings = config('auth.login');
//
//        parent::__construct($em);
//    }

    /**
     * Request passkey registration.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param Response                                 $response The response Object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return Response
     */
    public function findLinkData(Request $request, Response $response): Response
    {
        $flash = Session::getFlash();

        try {
            $validator = new Validator($request->getParsedBody());

            $data = $validator->validate([
                'email' => 'string',
            ]);


            if ($validator->passes()) {
                $challengeManager = app()->resolve(ChallengeManagerInterface::class);

                $repository = app()->resolve(EntityManager::class)->getRepository(User::class);
                $user = $repository->findOneBy(['email' => $data['email'] ?? '']);

                if (!$user) {
                    throw new \Exception("User not found");
                }

                $repository = app()->resolve(EntityManager::class)->getRepository(PasskeyCredential::class);
                $creds = $repository->findOneBy(['user' => $user->getId()]);

                $challenge = $challengeManager->createChallenge();

                $codec = new Codecs\Credential();
                $credentials = array_map(function ($row) use ($codec) {
                    return $codec->decode($row->getCredential());
                }, [$creds]);

                $credentialContainer = new CredentialContainer($credentials);

                Session::set('authenticating_user_id', $user->getId());

                $data = [
                    'challengeB64' => $challenge->getBase64(),
                    'credential_ids' => $credentialContainer->getBase64Ids(),
                ];

                $response = $response->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');

                $response->getBody()->write(json_encode($data));
            } else {
                throw new \Exception("User not found2");
            }
        } catch (\Exception $e) {
            $flash->add('error', 'Could not login user ');
            die("ERROR " . $e->getMessage());
        }


        return $response;
    }

    /**
     * Verify the passkey registration.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param Response                                 $response The response Object.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return Response
     */
    public function login(Request $request, Response $response): Response
    {
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $parser = new ArrayBufferResponseParser();
            $getResponse = $parser->parseGetResponse($data);
            $userHandle = $getResponse->getUserHandle();
            $challengeManager = app()->resolve(ChallengeManagerInterface::class);
            $rp = app()->resolve(RelyingPartyInterface::class);

            $repository = app()->resolve(EntityManager::class)->getRepository(User::class);
            $user = $repository->find(Session::get('authenticating_user_id'));

            $repository = app()->resolve(EntityManager::class)->getRepository(PasskeyCredential::class);
            $creds = $repository->findOneBy(['user' => $user->getId()]);

            $codec = new Codecs\Credential();
            $credentials = array_map(function ($row) use ($codec) {
                return $codec->decode($row->getCredential());
            }, [$creds]);

            $credentialContainer = new CredentialContainer($credentials);

            if ($userHandle !== null && $userHandle !== Session::get('authenticating_user_id')) {
                throw new \Exception('User handle does not match authenticating user');
            }

            $updatedCredential = $getResponse->verify($challengeManager, $rp, $credentialContainer);

            $codec = new Codecs\Credential();
            $encodedCredential = $codec->encode($updatedCredential);

            $creds->setStorageId($updatedCredential->getStorageId());
            $creds->setUser($user);
            $creds->setCredential($encodedCredential);


            $em = app()->resolve(EntityManager::class);
            $em->persist($creds);
            $em->flush();


            $guard = new Guard();
            $guard->login($user);

            $data = [
                'status' => 'OK',
                'redirect_to' =>  app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo),
            ];

            $response = $response->withStatus(200)
                ->withHeader('Content-Type', 'application/json');

            $response->getBody()->write(json_encode($data));

            log_debug("LOGIN OK");
        } catch (\Exception $e) {
            // Verification failed. Send an error to the user?
            log_error("verifyLinkRequestData: " . $e->getMessage());

            $response = $response->withStatus(403);
            $response->getBody()->write('HTTP/1.1 403 Unauthorized');
        }

        return $response;
    }
}
