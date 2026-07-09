<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Catalog\Sede;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar o crear el usuario admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@makabro.com'],
            [
                'cedula' => '123456789',
                'name' => 'Administrador General',
                'email' => 'admin@makabro.com',
                'password' => Hash::make('Admin123456789.*'),
                'cargo' => 'Administrador General',
                'telefono' => '3001234567',
                'activo' => true,
            ]
        );

        // Asignar a todas las sedes
        $sedes = Sede::all();
        foreach ($sedes as $sede) {
            if (!$admin->sedes()->where('sede_id', $sede->id)->exists()) {
                $admin->sedes()->attach($sede->id, [
                    'activo' => true,
                    'cargo_sede' => 'Administrador General',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ============================================
        // ASIGNAR ROL SUPER ADMINISTRADOR
        // ============================================
        $role = Role::where('name', 'super_admin')->first();
        
        if ($role) {
            $admin->assignRole($role);
            $this->command->info('✅ Usuario Administrador creado como Super Administrador');
        }

        // ============================================
        // ASIGNAR TODOS LOS PERMISOS DIRECTAMENTE (POR SI ACASO)
        // ============================================
        $todosLosPermisos = Permission::all();
        if ($todosLosPermisos->count() > 0) {
            $admin->syncPermissions($todosLosPermisos);
            $this->command->info('✅ Usuario Administrador tiene todos los permisos (' . $todosLosPermisos->count() . ' permisos)');
        }

        $this->command->info('✅ Usuario Administrador creado exitosamente.');
        $this->command->info('   📧 Email: admin@makabro.com');
        $this->command->info('   🔑 Contraseña: Admin123456789.*');
    }
}