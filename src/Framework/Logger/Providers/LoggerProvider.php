<?php

namespace App\src\Framework\Logger\Providers;

use App\src\Framework\Provider;


class LoggerProvider extends Provider
{

    /**
     * Register auth routes.
     *
     * @return void
     */
    public function register(): void
    {
        require __DIR__ . '/../Helpers.php';
    }
}

