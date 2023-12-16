<?php

namespace App\src\Framework\Bootstrap\Interfaces;

use App\Application;
use App\src\Framework\Bootstrap\Kernel;

interface ModuleInterface
{
    /**
     * Run a bootstrapping module.
     *
     * @param \App\Application                    $app    Reference to the Application instance.
     * @param \App\src\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void;
}