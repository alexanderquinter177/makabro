<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalog\Producto;

class RecetaSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar o crear la sub-receta
        $subSalsa = Producto::firstOrCreate(
            ['nombre' => 'SUB SALSA HAMBURGUESERA'],
            [
                'nombre' => 'SUB SALSA HAMBURGUESERA',
                'tipo' => 'subensamble',
                'categoria_id' => $categoriaSubRecetas->id,
                'unidad_medida_id' => $unidadGramo->id,
                'precio_unitario' => 0,
                'activo' => true,
            ]
        );

        // Definir ingredientes con sus cantidades
        $ingredientes = [
            ['nombre' => 'Mayonesa', 'cantidad' => 500],
            ['nombre' => 'Aderezo bbq', 'cantidad' => 50],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 20],
            ['nombre' => 'Zumo de limón', 'cantidad' => 20],
            ['nombre' => 'Paprika', 'cantidad' => 2],
            ['nombre' => 'Salsa de humo', 'cantidad' => 4],
            ['nombre' => 'Salsa inglesa', 'cantidad' => 30],
            ['nombre' => 'Mostaza', 'cantidad' => 50],
            ['nombre' => 'Sub pepinillo encurtido', 'cantidad' => 20],
            ['nombre' => 'Azucar Blanca', 'cantidad' => 12],
        ];

        // Asignar ingredientes a la receta
        foreach ($ingredientes as $item) {
            $ingrediente = Producto::where('nombre', $item['nombre'])->first();
            
            if ($ingrediente) {
                $subSalsa->ingredientes()->attach($ingrediente->id, [
                    'cantidad' => $item['cantidad'],
                    'nota' => null,
                ]);
            }
        }
    }
}