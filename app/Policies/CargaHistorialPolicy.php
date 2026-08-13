<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Inventory\CargaHistorial;
use Illuminate\Auth\Access\HandlesAuthorization;

class CargaHistorialPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('historial_carga.ver_listado');
    }

    public function view(User $user, CargaHistorial $cargaHistorial): bool
    {
        return $user->activo && $user->hasPermissionTo('historial_carga.ver_detalle');
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('historial_carga.crear');
    }

    public function update(User $user, CargaHistorial $cargaHistorial): bool
    {
        return $user->activo && $user->hasPermissionTo('historial_carga.editar');
    }

    public function delete(User $user, CargaHistorial $cargaHistorial): bool
    {
        return $user->activo && $user->hasPermissionTo('historial_carga.eliminar');
    }
}
