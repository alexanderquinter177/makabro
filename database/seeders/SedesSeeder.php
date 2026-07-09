<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalog\Sede;

class SedesSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Sede::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $sedes = [
            [
                'nombre' => 'MAKABRO BELLO',
                'codigo' => 'MBE',
                'marca' => 'Makabro',                
                'activo' => true,
            ],
            [
                'nombre' => 'MAKABRO BOSTON',
                'codigo' => 'MBOS',
                'marca' => 'Makabro',              
                'activo' => true,
            ],
            [
                'nombre' => 'MAKABRO LA 80',
                'codigo' => 'ML80',
                'marca' => 'Makabro',
                'activo' => true,
            ],
            [
                'nombre' => 'MAKABRO CAMPO VALDES',
                'codigo' => 'MCV',
                'marca' => 'Makabro',
                'activo' => true,
            ],
            [
                'nombre' => 'MAKABRO ENVIGADO',
                'codigo' => 'MENV',
                'marca' => 'Makabro',
                'activo' => true,
            ],
            [
                'nombre' => 'DOLORES',
                'codigo' => 'DOL',
                'marca' => 'La Dolores',
                'activo' => true,
            ],
            [
                'nombre' => 'LA PURISIMA',
                'codigo' => 'LPU',
                'marca' => 'La Purísima',
                'activo' => true,
            ],
            [
                'nombre' => 'CARMELA PRIMAVERA',
                'codigo' => 'CPR',
                'marca' => 'Carmela',
                'activo' => true,
            ],
            [
                'nombre' => 'LA BRASA',
                'codigo' => 'LBR',
                'marca' => 'La Grasa/Brusco',
                'activo' => true,
            ],
        ];

        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
