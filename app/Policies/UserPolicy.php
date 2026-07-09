<?php

namespace App\Policies;

use App\Models\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.ver_listado');
    }

    public function view(User $user, User $model): bool
    {
        return $user->activo && ($user->hasPermissionTo('usuario.ver_detalle') || $user->id === $model->id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.crear');
    }

    public function update(User $user, User $model): bool
    {
        return $user->activo && ($user->hasPermissionTo('usuario.editar') || $user->id === $model->id);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.eliminar') && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.restaurar');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->activo && $user->hasPermissionTo('usuario.eliminar_permanente');
    }
}
