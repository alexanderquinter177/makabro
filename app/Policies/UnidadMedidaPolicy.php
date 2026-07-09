<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Catalog\UnidadMedida;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnidadMedidaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('unidad.ver_listado');
    }

    public function view(User $user, UnidadMedida $unidadMedida): bool
    {
        return $user->activo && $user->hasPermissionTo('unidad.ver_detalle');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('unidad.crear');
    }

    public function update(User $user, UnidadMedida $unidadMedida): bool
    {
        return $user->activo && $user->hasPermissionTo('unidad.editar');
    }

    public function delete(User $user, UnidadMedida $unidadMedida): bool
    {
        return $user->activo && $user->hasPermissionTo('unidad.eliminar');
    }
}
