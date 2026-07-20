<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Inventory\SalesReportImport;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesReportImportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('reporte_ventas.ver_listado');
    }

    public function view(User $user, SalesReportImport $salesReportImport): bool
    {
        return $user->activo
            && $user->hasPermissionTo('reporte_ventas.ver_detalle')
            && $user->tieneAccesoASede($salesReportImport->sede_id);
    }

    public function create(User $user): bool
    {
        return $user->activo && $user->hasPermissionTo('reporte_ventas.importar');
    }

    public function update(User $user, SalesReportImport $salesReportImport): bool
    {
        return false; // Los reportes son de solo lectura, no se editan
    }

    public function delete(User $user, SalesReportImport $salesReportImport): bool
    {
        return $user->activo
            && $user->hasPermissionTo('reporte_ventas.eliminar')
            && $user->tieneAccesoASede($salesReportImport->sede_id);
    }
}
