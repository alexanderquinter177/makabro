<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Catalog\Sede;
use Illuminate\Support\Facades\Auth;

class SedeSwitcher extends Component
{
    /**
     * Cambia la sede activa en sesión (solo sedes con permiso).
     */
    public function switchSede(int $sedeId): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Verificar que el usuario tenga acceso activo a esa sede
        $tieneAcceso = $user->sedes()
            ->where('sedes.id', $sedeId)
            ->wherePivot('activo', true)
            ->exists();

        if (!$tieneAcceso) {
            return;
        }

        session(['sede_id' => $sedeId]);
        session()->save();

        // Redirige a la misma página para que todos los datos se refresquen
        $this->redirect(request()->header('Referer') ?? '/', navigate: false);
    }

    public function render()
    {
        $user = Auth::user();

        $sedes     = $user?->sedes()->wherePivot('activo', true)->orderBy('nombre')->get() ?? collect();
        $sedeActual = Sede::find(session('sede_id'));

        return view('livewire.sede-switcher', [
            'sedes'      => $sedes,
            'sedeActual' => $sedeActual,
        ]);
    }
}
