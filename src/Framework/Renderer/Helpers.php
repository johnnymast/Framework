<?php

use App\Framework\Renderer\Interfaces\RendererInterface;
use App\Framework\Renderer\View;
use App\Framework\Router\Interfaces\CurrentRouteInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Routing\RouteContext;

if (!function_exists('__')) {
    /**
     * Polyfill for the __ if getText is not enabled in
     * php.
     *
     * @param string $string The string to translate.
     *
     * @return string
     */
    function __(string $string): string
    {
        return $string;
    }
}

/**
 * Return a new view.
 *
 * @param string               $view The view.
 * @param array<string, mixed> $args The arguments for the view.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return \Psr\Http\Message\ResponseInterface
 */
function view(string $view = '', array $args = []): ResponseInterface
{
    $renderer = app()->resolve(RendererInterface::class);
    $view = new View($view, $args);

    return $renderer->render($view);
}

/**
 * Return a new view from text.
 *
 * @param string               $view The view.
 * @param array<string, mixed> $args The arguments for the view.
 *
 * @throws \Psr\Container\ContainerExceptionInterface
 * @throws \Psr\Container\NotFoundExceptionInterface
 * @return string
 */
function viewAsText(string $view = '', array $args = []): string
{
    $renderer = app()->resolve(RendererInterface::class);
    $view = new View($view, $args);

    return (string)$renderer->render($view)->getBody();
}

/**
 * Read a value from the environment.
 *
 * @param string $key     They key to read.
 * @param string $default The default value.
 *
 * @return string
 */
function env(string $key, string $default = ''): string
{
    if (!empty($_ENV[$key])) {
        return $_ENV[$key];
    }
    return $default;
}

/**
 * Display old posted form data.
 *
 * @param string $string  The posted key.
 * @param string $default The default value.
 *
 * @return string
 */
function old(string $string, string $default = ''): string
{
    $data = app()
        ->getRequest()
        ->getParsedBody();

    if (!empty($data[$string])) {
        return $data[$string];
    }
    return $default;
}

/**
 * Create a route for inside blade.
 *
 * @param string        $route The name of the route.
 * @param array<string> $data  The arguments for the route.
 *
 * @return string
 */
function route(string $route, array $data = []): string
{
    return app()->getRouteCollector()->getRouteParser()->urlFor($route, $data);
}

/**
 * Check if the current route is active.
 *
 * Note: This needs $app->addRoutingMiddleware(); just before $app->run()
 * in index.php.
 *
 *
 * @param mixed $data The route(s) to check.
 *
 * @return bool True if the current route is active, false otherwise.
 */
function is_active_route(mixed $data): bool
{
    $route = app()->resolve(CurrentRouteInterface::class);

    if (!is_array($data)) {
        $data = [$data];
    }

    foreach ($data as $name) {
        if ($route->getName() === $name) {
            return true;
        }
    }
    return false;
}