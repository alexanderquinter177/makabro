<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Instanciar la aplicación primero
$app = Application::configure(basePath: dirname(__DIR__))
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

/* 
 |--------------------------------------------------------------------------
 | Auto-crear carpetas del Storage (Ideal para Docker/Railway/Volúmenes)
 |--------------------------------------------------------------------------
 | Ejecutado DESPUÉS de instanciar la app para evitar errores 255 
 | durante php artisan config:cache
 |--------------------------------------------------------------------------
 */
$storagePaths = [
    $app->storagePath('app/public'),
    $app->storagePath('app/private/temp-imports'),
    $app->storagePath('app/temp-imports'),
    $app->storagePath('app/livewire-tmp'),
    $app->storagePath('framework/cache/data'),
    $app->storagePath('framework/sessions'),
    $app->storagePath('framework/views'),
    $app->storagePath('logs'),
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

// 2. Retornar la aplicación
return $app;