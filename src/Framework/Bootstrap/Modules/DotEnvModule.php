<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\src\Framework\Bootstrap\Kernel;
use Dotenv\Dotenv;

class DotEnvModule implements ModuleInterface
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
        $isDocker = fn() => is_file("/.dockerenv");
        $name = $isDocker() ? ".env-docker" : ".env-local";

        $dotenv = Dotenv::createImmutable(PROJECT_PATH, $name);
        $dotenv->load();
    }
}