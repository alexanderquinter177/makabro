<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Catalog\Producto;
use App\Models\Catalog\Categoria;
use Illuminate\Support\Str;

class SubRecetasSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📦 Cargando Subrecetas...');

        // Obtener una categoría para las subrecetas
        $categoriaSubrecetas = Categoria::firstOrCreate(
            ['nombre' => 'Subrecetas'],
            [
                'nombre' => 'Subrecetas',
                'slug' => Str::slug('Subrecetas'), // 👈 AGREGAR SLUG
                'descripcion' => 'Subrecetas para preparación de platos',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info("📂 Usando categoría: '{$categoriaSubrecetas->nombre}' (ID: {$categoriaSubrecetas->id})");

        // ========================================
        // 1. SUB SALSA HAMBURGUESERA
        // ========================================
        $this->crearSubreceta('SUB SALSA HAMBURGUESERA', $categoriaSubrecetas->id, [
            ['nombre' => 'Mayonesa', 'cantidad' => 500],
            ['nombre' => 'Aderezo bbq', 'cantidad' => 50],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 20],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 20],
            ['nombre' => 'Paprika', 'cantidad' => 2],
            ['nombre' => 'Salsa de humo', 'cantidad' => 4],
            ['nombre' => 'Salsa inglesa', 'cantidad' => 30],
            ['nombre' => 'Mostaza', 'cantidad' => 50],
            ['nombre' => 'Sub pepinillo encurtido', 'cantidad' => 20],
            ['nombre' => 'Azucar blanca', 'cantidad' => 12],
        ]);

        // ========================================
        // 2. SUB MAYOCILANTRO
        // ========================================
        $this->crearSubreceta('SUB MAYOCILANTRO', $categoriaSubrecetas->id, [
            ['nombre' => 'Mayonesa', 'cantidad' => 500],
            ['nombre' => 'Ajo pelado', 'cantidad' => 40],
            ['nombre' => 'Cilantro', 'cantidad' => 70],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
            ['nombre' => 'Pimienta negra', 'cantidad' => 0.5],
            ['nombre' => 'Sal común', 'cantidad' => 5],
            ['nombre' => 'Azucar blanca', 'cantidad' => 100],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 30],
        ]);

        // ========================================
        // 3. SUB MAYOCEBOLLA DULCE
        // ========================================
        $this->crearSubreceta('SUB MAYOCEBOLLA DULCE', $categoriaSubrecetas->id, [
            ['nombre' => 'Mayonesa', 'cantidad' => 500],
            ['nombre' => 'Sub cebolla caramelizada', 'cantidad' => 200],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
            ['nombre' => 'Mostaza', 'cantidad' => 40],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Miel', 'cantidad' => 30],
            ['nombre' => 'Paprika', 'cantidad' => 10],
            ['nombre' => 'Salsa de humo', 'cantidad' => 30],
        ]);

        // ========================================
        // 4. SUB CEBOLLA CARAMELIZADA
        // ========================================
        $this->crearSubreceta('SUB CEBOLLA CARAMELIZADA', $categoriaSubrecetas->id, [
            ['nombre' => 'Cebolla blanca', 'cantidad' => 500],
            ['nombre' => 'Azucar morena', 'cantidad' => 50],
            ['nombre' => 'Salsa inglesa', 'cantidad' => 30],
            ['nombre' => 'Mantequilla', 'cantidad' => 20],
            ['nombre' => 'Tres Cordillera', 'cantidad' => 1],
        ]);

        // ========================================
        // 5. SUB BBQ DE TAMARINDO Y RON
        // ========================================
        $this->crearSubreceta('SUB BBQ DE TAMARINDO Y RON', $categoriaSubrecetas->id, [
            ['nombre' => 'Aderezo bbq', 'cantidad' => 1000],
            ['nombre' => 'Rend zumo de Naranja', 'cantidad' => 200],
            ['nombre' => 'Azucar blanca', 'cantidad' => 300],
            ['nombre' => 'Ron Cortez Oro', 'cantidad' => 100],
            ['nombre' => 'Agua', 'cantidad' => 1000],
            ['nombre' => 'Tamarindo', 'cantidad' => 500],
        ]);

        // ========================================
        // 6. SUB CARNE DE HAMBURGUESA
        // ========================================
        $this->crearSubreceta('SUB CARNE DE HAMBURGUESA', $categoriaSubrecetas->id, [
            ['nombre' => 'Morrillo', 'cantidad' => 400],
            ['nombre' => 'Pecho de res', 'cantidad' => 600],
            ['nombre' => 'Salsa inglesa', 'cantidad' => 20],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Mostaza', 'cantidad' => 20],
            ['nombre' => 'Pasta de ajo', 'cantidad' => 15],
            ['nombre' => 'Pimienta negra', 'cantidad' => 1],
        ]);

        // ========================================
        // 7. SUB HARINA COMPUESTA
        // ========================================
        $this->crearSubreceta('SUB HARINA COMPUESTA', $categoriaSubrecetas->id, [
            ['nombre' => 'Harina de trigo', 'cantidad' => 500],
            ['nombre' => 'Sal común', 'cantidad' => 20],
            ['nombre' => 'Paprika', 'cantidad' => 20],
            ['nombre' => 'Cebolla en polvo', 'cantidad' => 10],
            ['nombre' => 'Ajo en polvo', 'cantidad' => 10],
            ['nombre' => 'Pimienta negra', 'cantidad' => 5],
            ['nombre' => 'Sazon completo badia', 'cantidad' => 5],
        ]);

        // ========================================
        // 8. SUB CHUTNEY DE MANGO
        // ========================================
        $this->crearSubreceta('SUB CHUTNEY DE MANGO', $categoriaSubrecetas->id, [
            ['nombre' => 'Mango tommy', 'cantidad' => 200],
            ['nombre' => 'Rend zumo de maracuya', 'cantidad' => 200],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 30],
            ['nombre' => 'Pimenton rojo', 'cantidad' => 30],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 200],
            ['nombre' => 'Azucar blanca', 'cantidad' => 150],
        ]);

        // ========================================
        // 9. SUB POLLO RELLENO
        // ========================================
        $this->crearSubreceta('SUB POLLO RELLENO', $categoriaSubrecetas->id, [
            ['nombre' => 'Pechuga filete', 'cantidad' => 250],
            ['nombre' => 'Tomates secos', 'cantidad' => 20],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Albahaca Fresca', 'cantidad' => 5],
        ]);

        // ========================================
        // 10. SUB PEPINILLO ENCURTIDO
        // ========================================
        $this->crearSubreceta('SUB PEPINILLO ENCURTIDO', $categoriaSubrecetas->id, [
            ['nombre' => 'Pepino Cohombro', 'cantidad' => 500],
            ['nombre' => 'Agua', 'cantidad' => 300],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 300],
            ['nombre' => 'Coriandro', 'cantidad' => 10],
            ['nombre' => 'Romero', 'cantidad' => 10],
            ['nombre' => 'Azucar blanca', 'cantidad' => 50],
            ['nombre' => 'Sal común', 'cantidad' => 50],
            ['nombre' => 'Pimienta negra', 'cantidad' => 10],
        ]);

        $this->command->info('✅ Todas las subrecetas fueron creadas exitosamente!');
        
        // Mostrar los códigos generados
        $this->command->info("\n📋 Códigos generados:");
        $subrecetas = Producto::where('tipo', 'subensamble')->get();
        foreach ($subrecetas as $sub) {
            $this->command->line("   {$sub->codigo} - {$sub->nombre}");
        }
    }

    /**
     * Crear una subreceta con sus ingredientes
     */
    private function crearSubreceta(string $nombre, int $categoriaId, array $ingredientes): void
    {
        // Buscar unidad de medida por defecto (gr o ID 4)
        $unidadGr = \App\Models\Catalog\UnidadMedida::where('abreviatura', 'gr')->first();
        $unidadCompraId = $unidadGr ? $unidadGr->id : 4;

        // Buscar o crear la subreceta (producto tipo 'subensamble')
        $subreceta = Producto::firstOrCreate(
            ['nombre' => $nombre],
            [
                'nombre' => $nombre,
                'tipo' => 'subensamble',
                'categoria_id' => $categoriaId,
                'unidad_compra_id' => $unidadCompraId,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Eliminar ingredientes antiguos (para evitar duplicados)
        DB::table('recetas_bom')
            ->where('producto_padre_id', $subreceta->id)
            ->delete();

        // Agregar los ingredientes
        $contador = 0;
        foreach ($ingredientes as $ingData) {
            $ingrediente = Producto::where('nombre', $ingData['nombre'])->first();
            
            if ($ingrediente) {
                DB::table('recetas_bom')->insert([
                    'producto_padre_id' => $subreceta->id,
                    'producto_hijo_id' => $ingrediente->id,
                    'cantidad' => $ingData['cantidad'],
                    'nota' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $contador++;
            } else {
                $this->command->warn("⚠️ Ingrediente no encontrado: '{$ingData['nombre']}' para '{$nombre}'");
            }
        }

        $this->command->info("✅ Subreceta '{$nombre}' creada con {$contador} ingredientes");
    }
}