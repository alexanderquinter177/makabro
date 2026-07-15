<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSelectedSede
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si el usuario está autenticado y activo
        if ($user && $user->activo) {
            $sedesActivas = $user->sedesActivas()->get();

            // Si el usuario no tiene ninguna sede activa asignada, cerrar sesión
            if ($sedesActivas->isEmpty()) {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'cedula' => 'Tu usuario no tiene sedes activas asignadas. Contacta al administrador.',
                ]);
            }

            // Si el usuario tiene exactamente 1 sede y no está en sesión, asignarla automáticamente
            if ($sedesActivas->count() === 1 && !session()->has('sede_id')) {
                session(['sede_id' => $sedesActivas->first()->id]);
            }

            // Si tiene más de una sede y aún no ha seleccionado ninguna
            if ($sedesActivas->count() > 1 && !session()->has('sede_id')) {
                // Evitamos redirección infinita en la misma página de selección
                if (!$request->is('select-sede') && !$request->routeIs('select-sede')) {
                    return redirect()->route('select-sede');
                }
            }
        }

        return $next($request);
    }
}
