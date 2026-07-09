<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Inventory\Inventario;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario.ver_listado');
    }

    public function view(User $user, Inventario $inventario): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario.ver_detalle') && $user->tieneAccesoASede($inventario->sede_id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario.crear');
    }

    public function update(User $user, Inventario $inventario): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario.editar') && $user->tieneAccesoASede($inventario->sede_id);
    }

    public function delete(User $user, Inventario $inventario): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario.eliminar') && $user->tieneAccesoASede($inventario->sede_id);
    }
}
