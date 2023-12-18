<?php

namespace App\Framework\Auth\Http\Middleware;

use App\Framework\Session\Facade\Session;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthPasskeyMiddleware implements MiddlewareInterface
{

    /**
     * Handle the middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Slim\Psr7\Response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $settings = config('auth.passkeys');
        $response = $handler->handle($request);

        if ($settings['enabled']) {
            $response = $response->withHeader(
                'Permissions-Policy', 'publickey-credentials-get=(self)'
            );
        }

        return $response;
    }
}

