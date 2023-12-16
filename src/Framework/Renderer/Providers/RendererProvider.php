<?php

namespace App\src\Framework\Renderer\Providers;


use App\src\Framework\Provider;

class RendererProvider extends Provider
{
    public function register(): void
    {
        require __DIR__ . '/../Helpers.php';
    }
}