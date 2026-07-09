<?php

namespace App\Policies;

use App\Models\Auth\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }
}
