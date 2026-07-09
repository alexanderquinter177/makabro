<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalog\Categoria;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        // Desactivar verificaciones de clave foránea temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Categoria::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categorias = [
            [
                'nombre' => 'Aseo',
                'slug' => 'aseo',
                'color' => '#007bff',
                'descripcion' => 'Productos de limpieza y aseo',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Bebidas',
                'slug' => 'bebidas',
                'color' => '#17a2b8',
                'descripcion' => 'Bebidas frías y calientes',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carnes',
                'slug' => 'carnes',
                'color' => '#dc3545',
                'descripcion' => 'Todo tipo de carnes: res, cerdo, pollo, pescado',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Cervezas',
                'slug' => 'cervezas',
                'color' => '#fd7e14',
                'descripcion' => 'Cervezas nacionales e importadas',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Desechables',
                'slug' => 'desechables',
                'color' => '#6c757d',
                'descripcion' => 'Vasos, platos, cubiertos, servilletas desechables',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Dulces',
                'slug' => 'dulces',
                'color' => '#e83e8c',
                'descripcion' => 'Postres y dulces',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Especias',
                'slug' => 'especias',
                'color' => '#e83e8c',
                'descripcion' => 'Condimentos y especias para cocina',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Frutas',
                'slug' => 'frutas',
                'color' => '#28a745',
                'descripcion' => 'Frutas frescas y procesadas',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Harinas',
                'slug' => 'harinas',
                'color' => '#ffc107',
                'descripcion' => 'Harinas, panes y productos de panadería',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lácteos',
                'slug' => 'lacteos',
                'color' => '#f8f9fa',
                'descripcion' => 'Leche, yogur, crema y derivados lácteos',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Licores',
                'slug' => 'licores',
                'color' => '#6f42c1',
                'descripcion' => 'Licores y bebidas alcohólicas',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Papelería',
                'slug' => 'papeleria',
                'color' => '#17a2b8',
                'descripcion' => 'Material de oficina y papelería',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Quesos',
                'slug' => 'quesos',
                'color' => '#fd7e14',
                'descripcion' => 'Todo tipo de quesos',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Reservas',
                'slug' => 'reservas',
                'color' => '#20c997',
                'descripcion' => 'Productos de reserva y almacenamiento',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Salsas',
                'slug' => 'salsas',
                'color' => '#dc3545',
                'descripcion' => 'Salsas y aderezos',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Verduras',
                'slug' => 'verduras',
                'color' => '#20c997',
                'descripcion' => 'Verduras y hortalizas frescas',
                'activo' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insertar todas las categorías
        Categoria::insert($categorias);
        
        $this->command->info('✅ ' . count($categorias) . ' categorías creadas exitosamente.');
    }
}