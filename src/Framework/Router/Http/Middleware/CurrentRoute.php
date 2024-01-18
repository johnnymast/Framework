<?php

namespace App\Framework\Router\Http\Middleware;

use App\Framework\Router\Interfaces\CurrentRouteInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Routing\RouteContext;

class CurrentRoute implements MiddlewareInterface
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
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        // return NotFound for non-existent route
        if (!empty($route)) {
            app()->bind(CurrentRouteInterface::class, $route);
        }

        return $handler->handle($request);
    }
}