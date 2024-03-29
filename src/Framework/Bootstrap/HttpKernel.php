<?php

namespace App\Framework\Bootstrap;

use App\Framework\Application;
use App\Framework\Auth\Http\Middleware\AuthMiddleware;
use App\Framework\Auth\Http\Middleware\AuthPasskeyMiddleware;
use App\Framework\Bootstrap\Modules\CSRFModule;
use App\Framework\Bootstrap\Modules\DatabaseModule;
use App\Framework\Bootstrap\Modules\DotEnvModule;
use App\Framework\Bootstrap\Modules\FacadeModule;
use App\Framework\Bootstrap\Modules\ModelRouteBinding;
use App\Framework\Bootstrap\Modules\MonologModule;
use App\Framework\Bootstrap\Modules\PasskeyModule;
use App\Framework\Bootstrap\Modules\ProvidersModule;
use App\Framework\Bootstrap\Modules\RendererModule;
use App\Framework\Bootstrap\Modules\SessionModule;
use App\Framework\Global\Http\Middleware\GlobalRequestMiddleware;
use App\Framework\Global\Http\Middleware\HttpErrorMiddleware;
use App\Framework\Router\Http\Middleware\CurrentRoute;


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
            DotEnvModule::class,
            DatabaseModule::class,
            FacadeModule::class,
            SessionModule::class,
            PasskeyModule::class,
            MonologModule::class,
            RendererModule::class,
            ModelRouteBinding::class,
            ProvidersModule::class,
            CSRFModule::class,
        ],
        'middleware' => [
            AuthMiddleware::class,
            GlobalRequestMiddleware::class,
            AuthPasskeyMiddleware::class,
            CurrentRoute::class,
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
     * @return \App\Framework\Application
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
     * @return \App\Framework\Application
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