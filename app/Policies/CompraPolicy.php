<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Purchase\Compra;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompraPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('compra.ver_listado');
    }

    public function view(User $user, Compra $compra): bool
    {
        return $user->activo && $user->hasPermissionTo('compra.ver_detalle') && $user->tieneAccesoASede($compra->sede_id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('compra.crear');
    }

    public function update(User $user, Compra $compra): bool
    {
        return $user->activo && $user->hasPermissionTo('compra.editar') && $user->tieneAccesoASede($compra->sede_id);
    }

    public function delete(User $user, Compra $compra): bool
    {
        return $user->activo && $user->hasPermissionTo('compra.eliminar') && $user->tieneAccesoASede($compra->sede_id);
    }
}
