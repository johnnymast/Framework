<?php

namespace App\src\Framework\Csrf\Providers;

use App\src\Framework\Provider;

class CsrfBladeSupportProvider extends Provider
{
    public function register(): void
    {
        require __DIR__ . '/../Helpers.php';
    }
}