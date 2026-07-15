<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SelectSede extends Component
{
    /**
     * Selecciona la sede activa en sesión.
     */
    public function selectSede(int $sedeId): void
    {
        $user = Auth::user();

        if (!$user) {
            $this->redirect('/login');
            return;
        }

        // Verificar que el usuario tenga acceso activo a esa sede
        $tieneAcceso = $user->sedes()
            ->where('sedes.id', $sedeId)
            ->wherePivot('activo', true)
            ->exists();

        if (!$tieneAcceso) {
            session()->flash('error', 'No tienes permiso para acceder a esta sede.');
            return;
        }

        session(['sede_id' => $sedeId]);
        session()->save();

        // Redirige al panel principal (dashboard)
        $this->redirect('/', navigate: false);
    }

    public function logout(): void
    {
        Auth::logout();
        session()->forget('sede_id');
        $this->redirect('/login');
    }

    public function render()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $sedes = $user->sedesActivas()->orderBy('nombre')->get();

        // Si no tiene sedes asignadas, desloguear
        if ($sedes->isEmpty()) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'cedula' => 'Tu usuario no tiene sedes activas asignadas. Contacta al administrador.',
            ]);
        }

        return view('livewire.auth.select-sede', [
            'sedes' => $sedes,
            'user'  => $user,
        ])
        ->layout('components.layouts.app')
        ->title('Seleccione su Sede');
    }
}
