<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catalog\Producto;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\UnidadMedida;
use Illuminate\Support\Str;

class SubRecetasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscar o crear la categoría "Sub recetas"
        $categoriaSubRecetas = Categoria::firstOrCreate(
            ['nombre' => 'Sub recetas'],
            [
                'slug' => Str::slug('Sub recetas'),
                'activo' => true,
            ]
        );

        // 2. Buscar unidades de medida
        $unidadGr = UnidadMedida::where('abreviatura', 'gr')->first();
        $unidadMl = UnidadMedida::where('abreviatura', 'ml')->first();
        $unidadUnd = UnidadMedida::where('abreviatura', 'und')->first();

        if (!$unidadGr) {
            $this->command->error('❌ Unidad "gr" no encontrada.');
            return;
        }

        // 3. Definir todas las sub-recetas
        $subRecetas = [
            // 1. SUB SALSA HAMBURGUESERA
            [
                'nombre' => 'SUB SALSA HAMBURGUESERA',
                'rendimiento' => 708,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Salsa clásica para hamburguesas',
                'ingredientes' => [
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
                ]
            ],
            // 2. SUB MAYOCILANTRO
            [
                'nombre' => 'SUB MAYOCILANTRO',
                'rendimiento' => 775.5,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Mayonesa con cilantro',
                'ingredientes' => [
                    ['nombre' => 'Mayonesa', 'cantidad' => 500],
                    ['nombre' => 'Ajo Pelado', 'cantidad' => 40],
                    ['nombre' => 'Cilantro', 'cantidad' => 70],
                    ['nombre' => 'Zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Pimienta Negra', 'cantidad' => 0.5],
                    ['nombre' => 'Sal Común', 'cantidad' => 5],
                    ['nombre' => 'Azucar Blanca', 'cantidad' => 100],
                    ['nombre' => 'Vinagre blanco', 'cantidad' => 30],
                ]
            ],
            // 3. SUB MAYOCEBOLLA DULCE
            [
                'nombre' => 'SUB MAYOCEBOLLA DULCE',
                'rendimiento' => 820,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Mayonesa con cebolla caramelizada',
                'ingredientes' => [
                    ['nombre' => 'Mayonesa', 'cantidad' => 500],
                    ['nombre' => 'Sub cebolla caramelizada', 'cantidad' => 200],
                    ['nombre' => 'Zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Mostaza', 'cantidad' => 40],
                    ['nombre' => 'Sal Común', 'cantidad' => 10],
                    ['nombre' => 'Miel', 'cantidad' => 30],
                    ['nombre' => 'Paprika', 'cantidad' => 10],
                    ['nombre' => 'Salsa de humo', 'cantidad' => 30],
                ]
            ],
            // 4. SUB CEBOLLA CARAMELIZADA
            [
                'nombre' => 'SUB CEBOLLA CARAMELIZADA',
                'rendimiento' => 601,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Cebolla caramelizada',
                'ingredientes' => [
                    ['nombre' => 'Cebolla Blanca', 'cantidad' => 500],
                    ['nombre' => 'Azucar morena', 'cantidad' => 50],
                    ['nombre' => 'Salsa inglesa', 'cantidad' => 30],
                    ['nombre' => 'Mantequilla', 'cantidad' => 20],
                    ['nombre' => 'Tres Cordillera', 'cantidad' => 1],
                ]
            ],
            // 5. SUB BBQ DE TAMARINDO Y RON
            [
                'nombre' => 'SUB BBQ DE TAMARINDO Y RON',
                'rendimiento' => 2100,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Salsa BBQ con tamarindo y ron',
                'ingredientes' => [
                    ['nombre' => 'Aderezo bbq', 'cantidad' => 1000],
                    ['nombre' => 'Zumo de Naranja', 'cantidad' => 200],
                    ['nombre' => 'Azucar Blanca', 'cantidad' => 300],
                    ['nombre' => 'Ron Cortez Oro', 'cantidad' => 100],
                    ['nombre' => 'Agua', 'cantidad' => 1000],
                    ['nombre' => 'Tamarindo', 'cantidad' => 500],
                ]
            ],
            // 6. SUB CARNE DE HAMBURGUESA
            [
                'nombre' => 'SUB CARNE DE HAMBURGUESA',
                'rendimiento' => 1086,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Mezcla para hamburguesas',
                'ingredientes' => [
                    ['nombre' => 'Morrillo', 'cantidad' => 400],
                    ['nombre' => 'Pecho De Res', 'cantidad' => 600],
                    ['nombre' => 'Salsa inglesa', 'cantidad' => 20],
                    ['nombre' => 'Sal Común', 'cantidad' => 10],
                    ['nombre' => 'Mostaza', 'cantidad' => 20],
                    ['nombre' => 'Pasta De Ajo Aderezo', 'cantidad' => 15],
                    ['nombre' => 'Pimienta Negra', 'cantidad' => 1],
                ]
            ],
            // 7. SUB HARINA COMPUESTA
            [
                'nombre' => 'SUB HARINA COMPUESTA',
                'rendimiento' => 570,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Mezcla de harina para empanizar',
                'ingredientes' => [
                    ['nombre' => 'Harina de trigo', 'cantidad' => 500],
                    ['nombre' => 'Sal Común', 'cantidad' => 20],
                    ['nombre' => 'Paprika', 'cantidad' => 20],
                    ['nombre' => 'Cebolla En Polvo', 'cantidad' => 10],
                    ['nombre' => 'Ajo En Polvo', 'cantidad' => 10],
                    ['nombre' => 'Pimienta Negra', 'cantidad' => 5],
                    ['nombre' => 'Sazon Completo Badia', 'cantidad' => 5],
                ]
            ],
            // 8. SUB CHUTNEY DE MANGO
            [
                'nombre' => 'SUB CHUTNEY DE MANGO',
                'rendimiento' => 810,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Chutney de mango agridulce',
                'ingredientes' => [
                    ['nombre' => 'Mango Tommy', 'cantidad' => 200],
                    ['nombre' => 'Zumo de maracuya', 'cantidad' => 200],
                    ['nombre' => 'Cebolla Blanca', 'cantidad' => 30],
                    ['nombre' => 'Pimenton Rojo', 'cantidad' => 30],
                    ['nombre' => 'Vinagre blanco', 'cantidad' => 200],
                    ['nombre' => 'Azucar Blanca', 'cantidad' => 150],
                ]
            ],
            // 9. SUB POLLO RELLENO
            [
                'nombre' => 'SUB POLLO RELLENO',
                'rendimiento' => 276,
                'unidad_rendimiento' => 'gr',
                'notas' => 'Pechuga rellena con tomates secos y queso',
                'ingredientes' => [
                    ['nombre' => 'Pechuga Filete', 'cantidad' => 250],
                    ['nombre' => 'Tomates secos', 'cantidad' => 20],
                    ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
                    ['nombre' => 'Albahaca Fresca', 'cantidad' => 5],
                ]
            ],
        ];

        // 4. Crear cada sub-receta
        foreach ($subRecetas as $recetaData) {
            $this->crearSubReceta($recetaData, $categoriaSubRecetas, $unidadGr);
        }
    }

    /**
     * Crear una sub-receta con sus ingredientes
     */
    private function crearSubReceta(array $recetaData, $categoria, $unidad): void
    {
        // Crear la sub-receta
        $subReceta = Producto::updateOrCreate(
            ['nombre' => $recetaData['nombre']],
            [
                'categoria_id' => $categoria->id,
                'tipo' => 'subensamble',
                'unidad_uso_id' => $unidad->id,
                'precio_compra' => 0,
                'unidad_compra_id' => null,
                'factor_conversion' => null,
                'costo_unitario' => null,
                'activo' => true,
                'proveedor_habitual' => null,
                'notas' => $recetaData['notas'] ?? "Rendimiento: {$recetaData['rendimiento']} {$recetaData['unidad_rendimiento']}",
            ]
        );

        // Generar código si no tiene
        if (empty($subReceta->codigo)) {
            $subReceta->codigo = Producto::generarCodigo('subensamble', $categoria->id);
            $subReceta->save();
        }

        $this->command->info("\n📝 Procesando: {$recetaData['nombre']}");

        // Asignar ingredientes
        $ingredientesAgregados = 0;
        $ingredientesNoEncontrados = [];

        foreach ($recetaData['ingredientes'] as $item) {
            $ingrediente = Producto::where('nombre', $item['nombre'])->first();

            if ($ingrediente) {
                $existe = $subReceta->ingredientes()
                    ->where('producto_hijo_id', $ingrediente->id)
                    ->exists();

                if (!$existe) {
                    $subReceta->ingredientes()->attach($ingrediente->id, [
                        'cantidad' => $item['cantidad'],
                        'nota' => null,
                    ]);
                    $ingredientesAgregados++;
                    $this->command->line("  ✅ {$item['nombre']} - {$item['cantidad']} gr");
                } else {
                    $this->command->line("  ⚠️ {$item['nombre']} - YA EXISTE");
                }
            } else {
                $ingredientesNoEncontrados[] = $item['nombre'];
                $this->command->error("  ❌ {$item['nombre']} - NO ENCONTRADO");
            }
        }

        // Mostrar resumen
        $costo = $subReceta->calcularCosto();
        $this->command->info("\n✅ Sub-receta creada: {$recetaData['nombre']}");
        $this->command->info("📊 Rendimiento: {$recetaData['rendimiento']} {$recetaData['unidad_rendimiento']}");
        $this->command->info("💰 Costo total: $" . number_format($costo, 0, ',', '.'));
        $this->command->info("🧾 Ingredientes agregados: {$ingredientesAgregados}/" . count($recetaData['ingredientes']));

        if (count($ingredientesNoEncontrados) > 0) {
            $this->command->warn("\n⚠️ Ingredientes no encontrados:");
            foreach ($ingredientesNoEncontrados as $nombre) {
                $this->command->warn("  - {$nombre}");
            }
        }
    }
}