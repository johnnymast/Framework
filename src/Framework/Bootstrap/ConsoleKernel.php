<?php

namespace App\src\Framework\Bootstrap;

use App\src\Framework\Bootstrap\Kernel;
use App\src\Framework\Bootstrap\Modules\ConsoleModule;
use App\src\Framework\Bootstrap\Modules\DatabaseModule;
use App\src\Framework\Bootstrap\Modules\DotEnvModule;
use App\src\Framework\Bootstrap\Modules\FacadeModule;
use App\src\Framework\Bootstrap\Modules\HelpersModule;
use App\src\Framework\Bootstrap\Modules\MonologModule;
use App\src\Framework\Bootstrap\Modules\ProvidersModule;
use App\src\Framework\Bootstrap\Modules\RendererModule;
use App\src\Framework\Bootstrap\Modules\SymfonyCommandModule;
use App\src\Framework\Console\Commands\FacadeMethodBlockGeneratorCommand;
use App\src\Framework\Console\Commands\MakeCommandCommand;
use App\src\Framework\Console\Commands\MakeControllerCommand;
use App\src\Framework\Console\Commands\MakeMailableCommand;
use App\src\Framework\Console\Commands\MakeMiddlewareCommand;
use App\src\Framework\Console\Commands\MakeModelCommand;
use App\src\Framework\Console\Commands\MakeProviderCommand;
use App\src\Framework\Console\Commands\RouteListCommand;

class ConsoleKernel extends Kernel
{

    /**
     * The boostrap modules to load.
     *
     * @var array|string[]
     */
    protected array $modules = [];

    /**
     * The commands to load.
     *
     * @var array|string[]
     */
    public array $commands = [];

    /**
     * Default values with modules,
     * middleware and error_middleware to
     * load from the bootstrapper internally.
     *
     * @var array|array[]
     */
    protected array $defaults = [
        'modules' => [
            HelpersModule::class,
            DotEnvModule::class,
            FacadeModule::class,
            MonologModule::class,
            ConsoleModule::class,
            SymfonyCommandModule::class,
            RendererModule::class,
            DatabaseModule::class,
            ProvidersModule::class,
        ],
        'commands' => [
            FacadeMethodBlockGeneratorCommand::class,
            MakeControllerCommand::class,
            MakeCommandCommand::class,
            MakeModelCommand::class,
            MakeMailableCommand::class,
            MakeMiddlewareCommand::class,
            MakeProviderCommand::class,
            RouteListCommand::class,
        ],
    ];


    /**
     * Merge code modules, middleware and error_middleware with those
     * loaded by default during bootstrap.
     *
     * @return void
     */
    protected function mergeValues(): void
    {
        $this->modules = array_merge($this->modules, $this->defaults['modules']);
        $this->commands = array_merge($this->commands, $this->defaults['commands']);
    }
}