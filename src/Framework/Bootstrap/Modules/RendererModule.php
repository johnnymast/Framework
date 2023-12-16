<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\src\Framework\Bootstrap\Kernel;
use App\src\Framework\Renderer\Interfaces\RendererInterface;
use App\src\Framework\Renderer\TemplateRenderer;

use function DI\value;

class RendererModule implements ModuleInterface
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
        $className = config('template.renderer');
        $settings = config('template.view');

        /**
         * @var \App\src\Framework\Renderer\Interfaces\RenderingEngineInterface $engine
         */
        $engine = new $className($settings['path'], $settings['cache']);

        $app->bind(
            'bladeExpressionParser',
            value(function (string $expression = '', string $separator = ',') {
                $parts = explode($separator, $expression);

                if (count($parts)) {
                    return array_map(fn($part) => trim(trim($part), "'"), $parts);
                }
                return [];
            })
        );

        $app->bind(RendererInterface::class, new TemplateRenderer($engine));
    }
}