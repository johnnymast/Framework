<?php

namespace App\src\Framework\Bootstrap\Modules;

use App\Application;
use App\src\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\src\Framework\Bootstrap\Kernel;
use App\src\Framework\Logger\Interfaces\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

class MonologModule implements ModuleInterface
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
        $files = config('logging.files');

        $logger = new Logger('Slim logger');
        $logger->pushHandler(new StreamHandler($files['info'], Level::Info));
        $logger->pushHandler(new StreamHandler($files['debug'], Level::Debug));
        $logger->pushHandler(new StreamHandler($files['error'], Level::Warning));
        $logger->pushHandler(new StreamHandler($files['error'], Level::Error));
        $logger->pushHandler(new StreamHandler($files['error'], Level::Emergency));
        $logger->pushHandler(new StreamHandler($files['error'], Level::Critical));

        $app->bind(LoggerInterface::class, $logger);
    }
}
