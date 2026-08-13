<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class HistorialCargaPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $acciones = [
            'ver_listado',
            'ver_detalle',
            'crear',
            'editar',
            'eliminar',
            'restaurar',
            'eliminar_permanente',
            'importar',
            'exportar_plantilla'
        ];

        $modulo = 'historial_carga';
        $permisosCreados = [];

        foreach ($acciones as $accion) {
            $permission = Permission::firstOrCreate([
                'name' => "{$modulo}.{$accion}",
                'guard_name' => 'web'
            ]);
            $permisosCreados[] = $permission;
        }

        // Asignar al rol super_admin si existe
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
            $this->command?->info("✅ Permisos de {$modulo} asignados al rol super_admin.");
        }

        // Asignar al rol admin si existe
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo(Permission::all());
            $this->command?->info("✅ Permisos de {$modulo} asignados al rol admin.");
        }

        // Asignar a gerente, admin_inventario y jefe_cocina si existen
        $rolesOperativos = ['gerente', 'admin_inventario', 'jefe_cocina'];
        foreach ($rolesOperativos as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo([
                    "{$modulo}.ver_listado",
                    "{$modulo}.ver_detalle",
                    "{$modulo}.crear",
                    "{$modulo}.importar",
                    "{$modulo}.exportar_plantilla",
                ]);
                $this->command?->info("✅ Permisos de {$modulo} asignados al rol {$roleName}.");
            }
        }

        $this->command?->info("✅ Permisos del módulo Historial de Carga registrados exitosamente.");
    }
}
