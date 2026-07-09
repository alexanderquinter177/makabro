<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Audit\Auditoria;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuditoriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('auditoria.ver_listado');
    }

    public function view(User $user, Auditoria $auditoria): bool
    {
        return $user->activo && $user->hasPermissionTo('auditoria.ver_detalle') && $user->tieneAccesoASede($auditoria->sede_id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('auditoria.ver_listado');
    }

    public function update(User $user, Auditoria $auditoria): bool
    {
        return $user->activo && $user->hasPermissionTo('auditoria.ver_listado') && $user->tieneAccesoASede($auditoria->sede_id);
    }

    public function delete(User $user, Auditoria $auditoria): bool
    {
        return $user->activo && $user->hasPermissionTo('auditoria.eliminar') && $user->tieneAccesoASede($auditoria->sede_id);
    }
}
