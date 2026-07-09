<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Inventory\Novedad;
use Illuminate\Auth\Access\HandlesAuthorization;

class NovedadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('novedad.ver_listado');
    }

    public function view(User $user, Novedad $novedad): bool
    {
        return $user->activo && $user->hasPermissionTo('novedad.ver_detalle') && $user->tieneAccesoASede($novedad->sede_id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('novedad.crear');
    }

    public function update(User $user, Novedad $novedad): bool
    {
        return $user->activo && $user->hasPermissionTo('novedad.editar') && $user->tieneAccesoASede($novedad->sede_id);
    }

    public function delete(User $user, Novedad $novedad): bool
    {
        return $user->activo && $user->hasPermissionTo('novedad.eliminar') && $user->tieneAccesoASede($novedad->sede_id);
    }
}
