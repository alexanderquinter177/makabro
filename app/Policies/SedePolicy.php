<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Catalog\Sede;
use Illuminate\Auth\Access\HandlesAuthorization;

class SedePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.ver_listado');
    }

    public function view(User $user, Sede $sede): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.ver_detalle') && $user->tieneAccesoASede($sede->id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.crear');
    }

    public function update(User $user, Sede $sede): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.editar') && $user->tieneAccesoASede($sede->id);
    }

    public function delete(User $user, Sede $sede): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.eliminar') && $user->tieneAccesoASede($sede->id);
    }

    public function restore(User $user, Sede $sede): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.restaurar');
    }

    public function forceDelete(User $user, Sede $sede): bool
    {
        return $user->activo && $user->hasPermissionTo('sede.eliminar_permanente');
    }
}
