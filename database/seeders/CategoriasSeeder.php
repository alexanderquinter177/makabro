<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\UnidadMedida;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
               // 🔥 CREAR CATEGORÍAS
        $this->crearCategorias();
        
        // 🔥 CREAR UNIDADES DE MEDIDA
        $this->crearUnidades();

        $this->command->info('✅ Categorías y Unidades de medida creadas exitosamente.');
    }

    /**
     * Crear categorías
     */
    private function crearCategorias(): void
    {
       // En CategoriasSeeder.php - Agregar estas categorías
        $categorias = [
            'Aceites y abarrotes',
            'Aves',
            'Bebidas',
            'Carnes',
            'Especias',
            'Frutas',
            'Frutas y verduras',
            'Harinas y panadería',
            'Lácteos',
            'Otros',
            'Quesos',
            'Rendimiento',
            'Salsas',
            'Sub receta',
            // ✅ NUEVAS CATEGORÍAS
            'Aseo y limpieza',      // Para productos de aseo
            'Desechables',           // Para productos desechables
            'Dulces y postres',      // Para dulces y anchetas
            'Papelería',             // Para productos de papelería
            'Decoración y reservas', // Para decoración y reservas
        ];
        

        $contador = 0;
        foreach ($categorias as $nombre) {
            Categoria::firstOrCreate(
                ['nombre' => $nombre],
                [
                    'nombre' => $nombre,
                    'slug' => Str::slug($nombre),
                    'activo' => true,
                ]
            );
            $contador++;
        }

        $this->command->info("📂 Categorías creadas: {$contador}");
    }

    /**
     * Crear unidades de medida
     */
    private function crearUnidades(): void
    {
        $unidades = [
            ['nombre' => 'Kilogramo', 'abreviatura' => 'kg'],
            ['nombre' => 'Litro', 'abreviatura' => 'lt'],
            ['nombre' => 'Unidad', 'abreviatura' => 'und'],
            ['nombre' => 'Gramo', 'abreviatura' => 'gr'],
            ['nombre' => 'Mililitro', 'abreviatura' => 'ml'],
        ];

        $contador = 0;
        foreach ($unidades as $data) {
            UnidadMedida::firstOrCreate(
                ['abreviatura' => $data['abreviatura']],
                [
                    'nombre' => $data['nombre'],
                    'activo' => true,
                ]
            );
            $contador++;
        }

        $this->command->info("📏 Unidades de medida creadas: {$contador}");
    }
}