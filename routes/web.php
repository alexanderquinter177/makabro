<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\SelectSede;

Route::redirect('/gestion', '/');
Route::redirect('/gestion/login', '/login');

Route::get('/select-sede', SelectSede::class)
    ->name('select-sede')
    ->middleware('auth');

Route::get('/debug-upload', function () {
    // Definir la ruta exacta que usa Livewire para subir archivos
    $livewireTmpPath = storage_path('app/livewire-tmp');
    
    // Intentar crear un archivo de prueba para ver si tenemos permisos
    $testFile = $livewireTmpPath . '/test_write.txt';
    $canWrite = false;
    
    try {
        if (!is_dir($livewireTmpPath)) {
            mkdir($livewireTmpPath, 0755, true);
        }
        file_put_contents($testFile, 'test');
        $canWrite = file_exists($testFile);
        if ($canWrite) {
            unlink($testFile); // Borrarlo si tuvo éxito
        }
    } catch (\Exception $e) {
        $canWrite = 'Error: ' . $e->getMessage();
    }

    return response()->json([
        '1_entorno' => config('app.env'),
        '2_https_forzado' => request()->isSecure() ? 'Sí (Correcto)' : 'No (Peligro de CORS)',
        '3_limite_peso_php' => ini_get('upload_max_filesize'),
        '4_carpeta_existe' => is_dir($livewireTmpPath) ? 'Sí' : 'No',
        '5_permisos_escritura' => $canWrite === true ? 'Sí (Correcto)' : $canWrite,
        '6_ruta_real_servidor' => $livewireTmpPath,
    ]);
});