<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Catalog\Producto;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('producto.ver_listado');
    }

    public function view(User $user, Producto $producto): bool
    {
        return $user->activo && $user->hasPermissionTo('producto.ver_detalle');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('producto.crear');
    }

    public function update(User $user, Producto $producto): bool
    {
        return $user->activo && $user->hasPermissionTo('producto.editar');
    }

    public function delete(User $user, Producto $producto): bool
    {
        return $user->activo && $user->hasPermissionTo('producto.eliminar');
    }
}
