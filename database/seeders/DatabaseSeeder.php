<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Primero las sedes
            SedesSeeder::class,
            
            // 2. Roles y Permisos (crea los roles y permisos)
            RolesAndPermissionsSeeder::class,
            
            // 3. Usuario Admin (solo 1 usuario)
            UsersSeeder::class,
            
            // 4. Catálogos
            UnidadesMedidaSeeder::class,
            CategoriasSeeder::class,
        ]);
    }
}