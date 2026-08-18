<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Check if using PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            // Instead of foreign_key_checks, use this for PostgreSQL
            DB::statement('SET CONSTRAINTS ALL DEFERRED;');
        }
        
        $this->call([
            SedesSeeder::class,
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            CategoriasSeeder::class,
            UnidadesMedidaSeeder::class,           
            ProductosSeeder::class,
            SubRecetasSeeder::class,
            RecetaSeeder::class,
           
        ]);
        
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('SET CONSTRAINTS ALL IMMEDIATE;');
        }
    }
}