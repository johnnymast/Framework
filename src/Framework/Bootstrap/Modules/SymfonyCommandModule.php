<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\src\Framework\Bootstrap\Kernel;

class SymfonyCommandModule implements ModuleInterface
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
        $console = $app->resolve(\Symfony\Component\Console\Application::class);

        foreach ($kernel->commands as $command) {
            $console->add(new $command);
        }
    }
}
