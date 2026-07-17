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
        // Desactivar verificaciones de clave foránea
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        Categoria::truncate();
        UnidadMedida::truncate();

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

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
        $categorias = [
            'Frutas y verduras',
            'Aves',
            'Carnes',
            'Especias',
            'Lácteos',
            'Quesos',
            'Salsas',
            'Aceites y abarrotes',
            'Harinas y panadería',
            'Bebidas',
            'Otros',
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