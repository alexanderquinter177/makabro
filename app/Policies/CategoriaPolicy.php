<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Catalog\Categoria;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('categoria.ver_listado');
    }

    public function view(User $user, Categoria $categoria): bool
    {
        return $user->activo && $user->hasPermissionTo('categoria.ver_detalle');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('categoria.crear');
    }

    public function update(User $user, Categoria $categoria): bool
    {
        return $user->activo && $user->hasPermissionTo('categoria.editar');
    }

    public function delete(User $user, Categoria $categoria): bool
    {
        return $user->activo && $user->hasPermissionTo('categoria.eliminar');
    }
}
