<?php

namespace App\Policies;

use App\Models\Auth\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.asignar_rol');
    }
}
