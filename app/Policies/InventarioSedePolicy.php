<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Inventory\InventarioSede;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioSedePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario_sede.ver_listado');
    }

    public function view(User $user, InventarioSede $inventarioSede): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario_sede.ver_detalle') && $user->tieneAccesoASede($inventarioSede->sede_id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario_sede.crear');
    }

    public function update(User $user, InventarioSede $inventarioSede): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario_sede.editar') && $user->tieneAccesoASede($inventarioSede->sede_id);
    }

    public function delete(User $user, InventarioSede $inventarioSede): bool
    {
        return $user->activo && $user->hasPermissionTo('inventario_sede.eliminar') && $user->tieneAccesoASede($inventarioSede->sede_id);
    }
}
