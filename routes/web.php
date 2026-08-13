<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\SelectSede;

use App\Http\Controllers\NovedadPrintController;
use App\Http\Controllers\HistorialCargaPrintController;

Route::redirect('/gestion', '/');
Route::redirect('/gestion/login', '/login');

Route::get('/select-sede', SelectSede::class)
    ->name('select-sede')
    ->middleware('auth');

Route::get('/novedades/{novedad}/imprimir', NovedadPrintController::class)
    ->name('novedades.imprimir')
    ->middleware('auth');

Route::get('/historial-cargas/{cargaHistorial}/imprimir', HistorialCargaPrintController::class)
    ->name('historial-cargas.imprimir')
    ->middleware('auth');

Route::get('/debug-upload', function () {
    $livewireTmpPath = storage_path('app/livewire-tmp');
    $tempImportsPath = storage_path('app/temp-imports');
    
    // Test escribir en livewire-tmp
    $testFileLivewire = $livewireTmpPath . '/test_write.txt';
    $canWriteLivewire = false;
    try {
        if (!is_dir($livewireTmpPath)) {
            @mkdir($livewireTmpPath, 0775, true);
        }
        file_put_contents($testFileLivewire, 'test_livewire');
        $canWriteLivewire = file_exists($testFileLivewire);
        if ($canWriteLivewire) {
            @unlink($testFileLivewire);
        }
    } catch (\Throwable $e) {
        $canWriteLivewire = 'Error: ' . $e->getMessage();
    }

    // Test escribir en temp-imports
    $testFileImports = $tempImportsPath . '/test_write.txt';
    $canWriteImports = false;
    try {
        if (!is_dir($tempImportsPath)) {
            @mkdir($tempImportsPath, 0775, true);
        }
        file_put_contents($testFileImports, 'test_imports');
        $canWriteImports = file_exists($testFileImports);
        if ($canWriteImports) {
            @unlink($testFileImports);
        }
    } catch (\Throwable $e) {
        $canWriteImports = 'Error: ' . $e->getMessage();
    }

    // Leer últimos errores del log de laravel si existen
    $logFile = storage_path('logs/laravel.log');
    $recentLogs = [];
    if (file_exists($logFile)) {
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $recentLogs = array_slice($lines, -15);
    }

    return response()->json([
        '1_entorno' => config('app.env'),
        '2_https_forzado' => request()->isSecure() ? 'Sí (Correcto)' : 'No (Peligro: Livewire puede bloquear uploads HTTP en Railway)',
        '3_limite_upload_max_filesize' => ini_get('upload_max_filesize'),
        '4_limite_post_max_size' => ini_get('post_max_size'),
        '5_config_livewire_upload' => config('livewire.temporary_file_upload'),
        '6_disco_local_path' => \Illuminate\Support\Facades\Storage::disk('local')->path(''),
        '7_livewire_tmp_existe' => is_dir($livewireTmpPath) ? 'Sí' : 'No',
        '8_livewire_tmp_permisos_escritura' => $canWriteLivewire === true ? 'OK (Escritura exitosa)' : $canWriteLivewire,
        '9_temp_imports_permisos_escritura' => $canWriteImports === true ? 'OK (Escritura exitosa)' : $canWriteImports,
        '10_ultimos_logs_servidor' => $recentLogs,
    ]);
});