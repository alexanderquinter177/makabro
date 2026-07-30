<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CheckSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->current_session_id) {
            $currentSessionId = session()->getId();

            if ($user->current_session_id !== $currentSessionId) {
                // Cerrar sesión ya que se inició sesión en otro dispositivo o navegador
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Notification::make()
                    ->title('Sesión finalizada')
                    ->body('Se ha cerrado tu sesión porque ingresaste desde otro dispositivo o navegador.')
                    ->danger()
                    ->send();

                return redirect()->guest('/login');
            }
        }

        return $next($request);
    }
}
