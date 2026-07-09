<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Inventory\KardexMovimiento;
use Illuminate\Auth\Access\HandlesAuthorization;

class KardexMovimientoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('kardex.ver_listado');
    }

    public function view(User $user, KardexMovimiento $kardexMovimiento): bool
    {
        return $user->activo && $user->hasPermissionTo('kardex.ver_detalle') && $user->tieneAccesoASede($kardexMovimiento->sede_id);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, KardexMovimiento $kardexMovimiento): bool
    {
        return false;
    }

    public function delete(User $user, KardexMovimiento $kardexMovimiento): bool
    {
        return false;
    }
}
