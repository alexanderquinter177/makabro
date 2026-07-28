<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Importación agregada para HTTPS

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-crear directorios del sistema de archivos con permisos 0775 (especialmente para Railway Volumes)
        $storagePaths = [
            storage_path('app/public'),
            storage_path('app/private/temp-imports'),
            storage_path('app/temp-imports'),
            storage_path('app/livewire-tmp'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            '/tmp/livewire-tmp',
        ];

        foreach ($storagePaths as $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0775, true);
            }
        }

        // Forzar HTTPS en producción (Railway) para evitar el bloqueo de Livewire
        if (config('app.env') === 'production' || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            URL::forceScheme('https');
        }

        // Implicitly grant "super_admin" role all permissions
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}