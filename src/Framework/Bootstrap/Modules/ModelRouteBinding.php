<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Kernel;
use App\src\Framework\RouteEntityBindingStrategy;
use Doctrine\ORM\EntityManager;

class ModelRouteBinding
{
    /**
     * Run the module.
     *
     * @param \App\Application                    $app    Reference to the Application instance.
     * @param \App\src\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $app->getRouteCollector()->setDefaultInvocationStrategy(
            new RouteEntityBindingStrategy(
                $app->resolve(EntityManager::class),
                $app->getResponseFactory()
            )
        );
    }
}