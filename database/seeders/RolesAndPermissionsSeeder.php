<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. CREAR TODOS LOS PERMISOS
        $this->crearTodosLosPermisos();

        // 2. CREAR ROLES Y ASIGNAR PERMISOS
        $this->crearRoles();

        $this->command->info('✅ Roles y permisos creados y asignados exitosamente.');
    }

    private function crearTodosLosPermisos(): void
    {
        // Acciones estándar de Filament/Spatie
        $estandar = ['ver_listado', 'ver_detalle', 'crear', 'editar', 'eliminar', 'restaurar', 'eliminar_permanente'];

        $modulos = [
            // ── Gestión Principal ────────────────────────────────────────────────
            'usuario'         => array_merge($estandar, ['resetear_password', 'asignar_rol']),
            'rol'             => $estandar,
            'empresa'         => $estandar,
            'sede'            => array_merge($estandar, ['asignar_usuario']),
            'auditoria'       => ['ver_listado', 'ver_detalle', 'exportar', 'eliminar'],

            // ── Catálogo ─────────────────────────────────────────────────────────
            'producto'        => array_merge($estandar, ['importar', 'exportar', 'cambiar_precio', 'cambiar_stock']),
            'categoria'       => $estandar,
            'unidad'          => $estandar,

            // ── Operación de Sede ────────────────────────────────────────────────
            'inventario_sede' => array_merge($estandar, ['exportar']),
            'kardex'          => array_merge($estandar, ['exportar', 'pdf', 'reporte']),
            'inventario'      => array_merge($estandar, ['aprobar', 'rechazar', 'exportar', 'pdf']),
            'novedad'         => array_merge($estandar, ['resolver', 'asignar', 'rechazar']),

            // ── Abastecimiento ───────────────────────────────────────────────────
            'proveedor'       => array_merge($estandar, ['cambiar_estado']),
            'compra'          => array_merge($estandar, ['aprobar', 'rechazar', 'pagar', 'recibir', 'exportar', 'pdf']),
            'compra_item'     => array_merge($estandar, ['recibir']),
            'historico_precios'=> array_merge($estandar, ['exportar']),

            // ── Ventas y Reportes ────────────────────────────────────────────────
            'reporte_ventas'  => array_merge($estandar, ['importar']),
            'dashboard'       => ['ver_financiero', 'ver_ventas', 'ver_kpis'],

            // ── Atención al Cliente (Call Center & WhatsApp IA) ─────────────────
            'chat'            => $estandar,
            'mensaje'         => $estandar,
            'bot_config'      => $estandar,
        ];

        $totalPermisos = 0;
        foreach ($modulos as $modulo => $acciones) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate([
                    'name' => "{$modulo}.{$accion}",
                    'guard_name' => 'web'
                ]);
                $totalPermisos++;
            }
        }

        $this->command->info("✅ {$totalPermisos} permisos creados/verificados en la base de datos.");
    }

    private function crearRoles(): void
    {
        // 1. SUPER ADMINISTRADOR - Acceso total absoluto
        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info('✅ Super Administrador creado con todos los permisos (' . $superAdmin->permissions->count() . ' permisos)');

        // 2. ADMIN - Administrador general (acceso completo)
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $admin->syncPermissions(Permission::all());
        $this->command->info('✅ Administrador General creado (' . $admin->permissions->count() . ' permisos)');

        // 3. GERENTE DE SEDE
        $gerente = Role::firstOrCreate([
            'name' => 'gerente',
            'guard_name' => 'web'
        ]);
        $gerente->syncPermissions([
            'sede.ver_detalle',
            'categoria.ver_listado', 'categoria.ver_detalle', 'categoria.crear', 'categoria.editar',
            'producto.ver_listado', 'producto.ver_detalle', 'producto.crear', 'producto.editar', 'producto.cambiar_precio',
            'proveedor.ver_listado', 'proveedor.ver_detalle', 'proveedor.crear', 'proveedor.editar',
            'unidad.ver_listado', 'unidad.ver_detalle',
            'inventario.ver_listado', 'inventario.ver_detalle', 'inventario.crear', 'inventario.editar', 'inventario.aprobar', 'inventario.rechazar',
            'inventario_sede.ver_listado', 'inventario_sede.ver_detalle', 'inventario_sede.crear', 'inventario_sede.editar',
            'novedad.ver_listado', 'novedad.ver_detalle', 'novedad.crear', 'novedad.editar', 'novedad.resolver', 'novedad.asignar',
            'kardex.ver_listado', 'kardex.ver_detalle', 'kardex.reporte',
            'compra.ver_listado', 'compra.ver_detalle', 'compra.crear', 'compra.editar', 'compra.aprobar', 'compra.rechazar', 'compra.recibir',
            'compra_item.ver_listado', 'compra_item.ver_detalle', 'compra_item.crear', 'compra_item.editar', 'compra_item.recibir',
            'reporte_ventas.ver_listado', 'reporte_ventas.ver_detalle', 'reporte_ventas.importar', 'reporte_ventas.eliminar',
            'dashboard.ver_financiero', 'dashboard.ver_ventas', 'dashboard.ver_kpis',
        ]);
        $this->command->info('✅ Gerente de Sede creado (' . $gerente->permissions->count() . ' permisos)');

        // 4. JEFE DE COCINA
        $jefeCocina = Role::firstOrCreate([
            'name' => 'jefe_cocina',
            'guard_name' => 'web'
        ]);
        $jefeCocina->syncPermissions([
            'categoria.ver_listado', 'categoria.ver_detalle',
            'producto.ver_listado', 'producto.ver_detalle',
            'unidad.ver_listado', 'unidad.ver_detalle',
            'inventario.ver_listado', 'inventario.ver_detalle',
            'inventario_sede.ver_listado', 'inventario_sede.ver_detalle',
            'novedad.ver_listado', 'novedad.ver_detalle', 'novedad.crear',
            'kardex.ver_listado', 'kardex.ver_detalle',
            'compra.ver_listado', 'compra.ver_detalle',
            'compra_item.ver_listado', 'compra_item.ver_detalle',
        ]);
        $this->command->info('✅ Jefe de Cocina creado (' . $jefeCocina->permissions->count() . ' permisos)');

        // 5. ADMINISTRADOR DE INVENTARIO
        $adminInventario = Role::firstOrCreate([
            'name' => 'admin_inventario',
            'guard_name' => 'web'
        ]);
        $adminInventario->syncPermissions([
            'producto.ver_listado', 'producto.ver_detalle', 'producto.cambiar_stock',
            'proveedor.ver_listado', 'proveedor.ver_detalle',
            'unidad.ver_listado', 'unidad.ver_detalle',
            'inventario.ver_listado', 'inventario.ver_detalle', 'inventario.crear', 'inventario.editar',
            'inventario.eliminar', 'inventario.aprobar', 'inventario.rechazar', 'inventario.exportar', 'inventario.pdf',
            'inventario_sede.ver_listado', 'inventario_sede.ver_detalle', 'inventario_sede.crear',
            'inventario_sede.editar', 'inventario_sede.eliminar', 'inventario_sede.exportar',
            'kardex.ver_listado', 'kardex.ver_detalle', 'kardex.exportar', 'kardex.pdf', 'kardex.reporte',
            'novedad.ver_listado', 'novedad.ver_detalle', 'novedad.crear', 'novedad.editar',
            'novedad.eliminar', 'novedad.resolver', 'novedad.asignar', 'novedad.rechazar',
            'compra.ver_listado', 'compra.ver_detalle', 'compra.recibir',
            'compra_item.ver_listado', 'compra_item.ver_detalle', 'compra_item.recibir',
            'reporte_ventas.ver_listado', 'reporte_ventas.ver_detalle', 'reporte_ventas.importar', 'reporte_ventas.eliminar',
        ]);
        $this->command->info('✅ Administrador de Inventario creado (' . $adminInventario->permissions->count() . ' permisos)');

        // 6. CAJERO
        $cajero = Role::firstOrCreate([
            'name' => 'cajero',
            'guard_name' => 'web'
        ]);
        $cajero->syncPermissions([
            'producto.ver_listado', 'producto.ver_detalle',
            'categoria.ver_listado', 'categoria.ver_detalle',
            'unidad.ver_listado', 'unidad.ver_detalle',
            'inventario.ver_listado', 'inventario.ver_detalle',
            'inventario_sede.ver_listado', 'inventario_sede.ver_detalle',
            'kardex.ver_listado', 'kardex.ver_detalle',
        ]);
        $this->command->info('✅ Cajero creado (' . $cajero->permissions->count() . ' permisos)');

        // 7. AUDITOR
        $auditor = Role::firstOrCreate([
            'name' => 'auditor',
            'guard_name' => 'web'
        ]);
        $auditor->syncPermissions([
            'auditoria.ver_listado', 'auditoria.ver_detalle', 'auditoria.exportar',
            'categoria.ver_listado', 'categoria.ver_detalle',
            'producto.ver_listado', 'producto.ver_detalle',
            'proveedor.ver_listado', 'proveedor.ver_detalle',
            'sede.ver_listado', 'sede.ver_detalle',
            'unidad.ver_listado', 'unidad.ver_detalle',
            'inventario.ver_listado', 'inventario.ver_detalle', 'inventario.exportar',
            'inventario_sede.ver_listado', 'inventario_sede.ver_detalle', 'inventario_sede.exportar',
            'kardex.ver_listado', 'kardex.ver_detalle', 'kardex.exportar', 'kardex.reporte',
            'novedad.ver_listado', 'novedad.ver_detalle',
            'compra.ver_listado', 'compra.ver_detalle', 'compra.exportar',
            'compra_item.ver_listado', 'compra_item.ver_detalle',
            'reporte_ventas.ver_listado', 'reporte_ventas.ver_detalle',
        ]);
        $this->command->info('✅ Auditor creado (' . $auditor->permissions->count() . ' permisos)');

        // 8. USUARIO BÁSICO
        $usuarioBasico = Role::firstOrCreate([
            'name' => 'usuario_basico',
            'guard_name' => 'web'
        ]);
        $usuarioBasico->syncPermissions([
            'categoria.ver_listado', 'categoria.ver_detalle',
            'producto.ver_listado', 'producto.ver_detalle',
            'unidad.ver_listado', 'unidad.ver_detalle',
        ]);
        $this->command->info('✅ Usuario Básico creado (' . $usuarioBasico->permissions->count() . ' permisos)');
    }
}