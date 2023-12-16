<?php

namespace App\src\Framework\Bootstrap;

use App\Application;
use App\src\Framework\Auth\Http\Middleware\AuthMiddleware;
use App\src\Framework\Bootstrap\Kernel;
use App\src\Framework\Bootstrap\Modules\CSRFModule;
use App\src\Framework\Bootstrap\Modules\DatabaseModule;
use App\src\Framework\Bootstrap\Modules\DotEnvModule;
use App\src\Framework\Bootstrap\Modules\FacadeModule;
use App\src\Framework\Bootstrap\Modules\HelpersModule;
use App\src\Framework\Bootstrap\Modules\ModelRouteBinding;
use App\src\Framework\Bootstrap\Modules\MonologModule;
use App\src\Framework\Bootstrap\Modules\ProvidersModule;
use App\src\Framework\Bootstrap\Modules\RendererModule;
use App\Http\Middleware\GlobalRequestMiddleware;
use App\Http\Middleware\HttpErrorMiddleware;

use function App\Framework\Bootstrap\app;

class HttpKernel extends Kernel
{
    /**
     * Default values with modules,
     * middleware and error_middleware to
     * load from the bootstrapper internally.
     *
     * @var array
     */
    protected array $defaults = [
        'modules' => [
            HelpersModule::class,
            DotEnvModule::class,
            FacadeModule::class,
            MonologModule::class,
            RendererModule::class,
            DatabaseModule::class,
            ModelRouteBinding::class,
            ProvidersModule::class,
            CSRFModule::class,
        ],
        'middleware' => [
            AuthMiddleware::class,
            GlobalRequestMiddleware::class,
        ],
        'error_middleware' => [
            HttpErrorMiddleware::class,
        ],
    ];

    /**
     * @var array|string[]
     */
    protected array $modules = [];

    /**
     * @var array|string[]
     */
    protected array $middleware = [];

    /**
     * @var array|string[]
     */
    protected array $errorMiddleware = [];

    /**
     * Merge code modules, middleware and error_middleware with those
     * loaded by default during bootstrap.
     *
     * @return void
     */
    protected function mergeValues(): void
    {
        $this->modules = array_merge($this->modules, $this->defaults['modules']);
        $this->middleware = array_merge($this->middleware, $this->defaults['middleware']);
        $this->errorMiddleware = array_merge($this->errorMiddleware, $this->defaults['error_middleware']);
    }

    /**
     * Register middleware to the Application.
     *
     * @return \App\Application
     */
    public function registerMiddleware(): Application
    {
        $app = app();

        foreach ($this->middleware as $middleware) {
            $app->add($middleware);
        }

        $app->addBodyParsingMiddleware();

        return $app;
    }

    /**
     * Register middleware to the Application.
     *
     * @return \App\Application
     */
    public function registerErrorMiddleware(): Application
    {
        $app = app();

        foreach ($this->errorMiddleware as $middleware) {
            $app->add($middleware);
        }

        $app->addBodyParsingMiddleware();

        return $app;
    }
}