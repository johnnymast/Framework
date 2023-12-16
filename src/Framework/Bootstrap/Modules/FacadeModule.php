<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\src\Framework\Bootstrap\Kernel;
use App\src\Framework\Facade;

class FacadeModule implements ModuleInterface
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
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($app);
    }
}