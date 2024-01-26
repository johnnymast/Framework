<?php

namespace App\Framework\Auth\Http\Middleware;

use App\Framework\Auth\Guard;
use App\Framework\Session\Facade\Session;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use PHPUnit\Logging\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthRequiredMiddleware implements MiddlewareInterface
{
    protected string $redirectTo = 'loggedout';

    /**
     * Handle the middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {

            $repository = app()->resolve(EntityManager::class)->getRepository(User::class);

            if (!Session::has('user')) {
                throw new Exception("User not logged in.");
            }
            $user = Session::get('user');
            $settings = config('auth.user');

            if (!$repository->find($user->getId())) {

                throw new Exception("User not found.");
            }


            if ($settings['require_confirmation'] && !$user->isActivated()) {
                /**
                 * Reload the user account from the database because
                 * the user could have activated their account since creating
                 * their account.
                 */
                $em = app()->resolve(EntityManager::class);
                $user = $em->getRepository(User::class)->find($user->getId());

                $response = app()
                    ->getResponseFactory()
                    ->createResponse();

                Session::set('user', $user);

                $response = $response
                    ->withStatus(302)
                    ->withBody($request->getBody())
                    ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor('auth.verification'));

            } else {
                $response = $handler->handle($request);
            }



        } catch (\Exception $e) {

            /**
             * Just to be sure the user is logged out.
             *
             * Note: added for the case that the admin deletes a user but the user is still in the session
             * then we use logout to remove the user from the session as well.
             */
            (new Guard())
                ->logout();

            $response = app()
                ->getResponseFactory()
                ->createResponse();

            $response = $response
                ->withStatus(302)
                ->withBody($request->getBody())
                ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo));

        }
        if (Session::has('user')) {



        } else {

            $response = app()
                ->getResponseFactory()
                ->createResponse();

            $response = $response
                ->withStatus(302)
                ->withBody($request->getBody())
                ->withHeader('Location', app()->getRouteCollector()->getRouteParser()->urlFor($this->redirectTo));
        }

        return $response;
    }
}
