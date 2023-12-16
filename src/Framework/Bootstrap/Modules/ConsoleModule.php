<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\src\Framework\Bootstrap\Kernel;

class ConsoleModule implements ModuleInterface
{

    /**
     * Run the module.
     *
     * @param \App\Application                    $app    Reference to the Application instance.
     * @param \App\src\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $app->bind(\Symfony\Component\Console\Application::class, new \Symfony\Component\Console\Application);
    }
}