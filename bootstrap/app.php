<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/* 
 |--------------------------------------------------------------------------
 | Auto-crear carpetas del Storage (Ideal para Docker/Railway/Volúmenes)
 |--------------------------------------------------------------------------
 */
$storagePaths = [
    storage_path('app/public'),
    storage_path('app/livewire-tmp'),
    storage_path('framework/cache/data'),
    storage_path('framework/sessions'),
    storage_path('framework/views'),
    storage_path('logs'),
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();