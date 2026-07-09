<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Catalog\Proveedor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProveedorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('proveedor.ver_listado');
    }

    public function view(User $user, Proveedor $proveedor): bool
    {
        return $user->activo && $user->hasPermissionTo('proveedor.ver_detalle');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('proveedor.crear');
    }

    public function update(User $user, Proveedor $proveedor): bool
    {
        return $user->activo && $user->hasPermissionTo('proveedor.editar');
    }

    public function delete(User $user, Proveedor $proveedor): bool
    {
        return $user->activo && $user->hasPermissionTo('proveedor.eliminar');
    }
}
