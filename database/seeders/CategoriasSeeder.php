<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\Producto;
use App\Models\Catalog\UnidadMedida;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        // Desactivar verificaciones de clave foránea temporalmente
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        Producto::truncate();
        Categoria::truncate();
        UnidadMedida::truncate();
        
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $categorias = $this->crearCategorias();
        $unidades = $this->getUnidades();

        // Datos de productos con conversión
        $productos = [
            // ==================== FRUTAS Y VERDURAS ====================
            ['nombre' => 'Aguacate Hass', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 8500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Aguacate Papelillo-Collin', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 12500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Aji Dulce', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 7000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Aji Picante', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 25000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Ajo Pelado', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 10000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Albahaca Fresca', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 16800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Apio', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 1900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Brotes', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 15000, 'unidad_compra' => 'gr', 'factor' => 50],
            ['nombre' => 'Chile Guajillo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 857100, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cebolla Blanca', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 9100, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cebolla Puerro', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cebolla Rama', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 4200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cebolla Roja', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 11500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cebollin Criollo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 12900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Champiñon Entero', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 30000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cilantro', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Durazno', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 9000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Fresa', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 4600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Gulupa', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Hierbabuena', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 18000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Hojas De Tamal', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 7800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Jalapeño Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 23000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Jengibre', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 14000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Laurel En Hojas', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 46000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Lechuga Cogollo Europeo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 6600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Lechuga Crespa Mix Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 10000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Lechuga Crespa Morada', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 10000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Limon Tahiti', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 4600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Limonaria', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 8500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Maiz Dulce', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 14700, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mango Tommy', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 8400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mezclum', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 62300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mandarina', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Manzana Roja', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 13900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Manzana Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 12300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Maracuya', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 7800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mora', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 2500, 'unidad_compra' => 'gr', 'factor' => 300],
            ['nombre' => 'Naranja', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 3400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Papa Francesa', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 12600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Papa Capira', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 2600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Papa Criolla Media', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Papa Criolla Tronco', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Patacon Paquete Por 40 Unidades', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 270000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pepino Cohombro', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 2000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Perejil Crespo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 16800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pimenton Rojo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 4200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Piña oro miel', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 3700, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Plátano Maduro', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 3600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Plátano Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 3600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Raíz China', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 2200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Rocotto', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 9700, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Romero', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 20000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tamarindo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 16900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tomate Cherry', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 14200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tomate chonto', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5700, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tomate Larga Vida', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 6500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Toronja', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 11600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Uchuva', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 3300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Zanahoria', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 3200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Uva Blanca', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 36000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Zuquini Verde Y Amarillo', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 5000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Batata', 'categoria' => 'Frutas y verduras', 'unidad' => 'lt', 'precio' => 20000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Kiwi', 'categoria' => 'Frutas y verduras', 'unidad' => 'kg', 'precio' => 7000, 'unidad_compra' => 'gr', 'factor' => 400],
            ['nombre' => 'Mazorca', 'categoria' => 'Frutas y verduras', 'unidad' => 'lt', 'precio' => 7000, 'unidad_compra' => 'ml', 'factor' => 1000],

            // ==================== AVES ====================
            ['nombre' => 'Alas de pollo', 'categoria' => 'Aves', 'unidad' => 'kg', 'precio' => 16800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Contramuslo', 'categoria' => 'Aves', 'unidad' => 'kg', 'precio' => 12900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pechuga Suprema', 'categoria' => 'Aves', 'unidad' => 'kg', 'precio' => 5300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pechuga Filete', 'categoria' => 'Aves', 'unidad' => 'kg', 'precio' => 27500, 'unidad_compra' => 'und', 'factor' => 1000],

            // ==================== CARNES ====================
            ['nombre' => 'Asado De Tira', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 21500, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Bondiola De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 20000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cañón De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Chicharron - Tocino', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 23000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Chorizo De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 3423, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Chorizo Argentino', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 3423, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Chorizo Burguer', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 2000, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Costichi', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 9000, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Costilla San Luis', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 9618, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Desmechada Copete', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 67000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Morrillo', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 32600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Espetada Al Pastor', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 0],
            ['nombre' => 'Jamón', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 0],
            ['nombre' => 'Medallones de Solomito', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 16180, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Morcilla Cóctelera', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 589, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Pecho De Res', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 30800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pierna De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 24500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Punta De Anca De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 6600, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Punta Hamburguesa (150g)', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 32500, 'unidad_compra' => 'und', 'factor' => 1000],
            ['nombre' => 'Solomito De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 6400, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Tocineta', 'categoria' => 'Carnes', 'unidad' => 'kg', 'precio' => 54500, 'unidad_compra' => 'gr', 'factor' => 34],
            ['nombre' => 'Tomahawk De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => 12600, 'unidad_compra' => 'und', 'factor' => 1],

            // ==================== ESPECIAS ====================
            ['nombre' => 'Ajinomoto', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 17000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Ajo En Polvo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 12000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Ajonjoli', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 25500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Albahaca Seca', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 4000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Almendra', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 24000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Arandanos', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 40000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Bicarbonato', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Canela Molida', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 21000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cebolla En Polvo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 17000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Clavo De Olor', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 54000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cocoa En Polvo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Colorante Liquido Amarillo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 5400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Colorante Liquido Rojo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 5400, 'unidad_compra' => 'und', 'factor' => 1000],
            ['nombre' => 'Comino', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 5400, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Coriandro', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 36000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Curcuma', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 29000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Curry', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Finas Hierbas Por 500 Gramos', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 36000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Flor De Jamaica', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 101000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Laurel En Polvo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Mani Simple', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 78400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mix De Flores', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 15400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Nuez Del Brasil', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 62000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Oregano En Polvo', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 31000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Oregano Escamas', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 32000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Panko', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 17500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Paprika', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 34000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Paprika Con Sal', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 22000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pimienta Cayena', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 250000, 'unidad_compra' => 'und', 'factor' => 1000],
            ['nombre' => 'Pimienta Molida', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 27000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Pimienta Negra', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 34000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Sazon Completo Badia', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 43800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tajín', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 68300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tomillo Escamas', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 14000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tomillo Molido', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 32000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Achiote', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 1, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Salsa de chocolate', 'categoria' => 'Especias', 'unidad' => 'kg', 'precio' => 22660, 'unidad_compra' => 'ml', 'factor' => 1000],

            // ==================== LÁCTEOS ====================
            ['nombre' => 'Crema Agria', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 20300, 'unidad_compra' => 'gr', 'factor' => 4000],
            ['nombre' => 'Crema De Leche Colanta', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 900],
            ['nombre' => 'Crema De Leche de vida', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 17100, 'unidad_compra' => 'gr', 'factor' => 900],
            ['nombre' => 'Crema Pastelera', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 13000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Leche Entera', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 3700, 'unidad_compra' => 'gr', 'factor' => 900],
            ['nombre' => 'Leche Condensada', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 36400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mantequilla', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 8250, 'unidad_compra' => 'und', 'factor' => 125],
            ['nombre' => 'Queso crema', 'categoria' => 'Lácteos', 'unidad' => 'kg', 'precio' => 81000, 'unidad_compra' => 'gr', 'factor' => 4000],

            // ==================== QUESOS ====================
            ['nombre' => 'Queso Azul', 'categoria' => 'Quesos', 'unidad' => 'kg', 'precio' => 66000, 'unidad_compra' => 'gr', 'factor' => 100],
            ['nombre' => 'Queso Cheddar Tajado', 'categoria' => 'Quesos', 'unidad' => 'und', 'precio' => 654, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Queso Gouda', 'categoria' => 'Quesos', 'unidad' => 'und', 'precio' => 830, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Queso Entero', 'categoria' => 'Quesos', 'unidad' => 'kg', 'precio' => 20900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Queso Mozzarella Tajado', 'categoria' => 'Quesos', 'unidad' => 'und', 'precio' => 583, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Queso Parmesano', 'categoria' => 'Quesos', 'unidad' => 'kg', 'precio' => 35000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Queso asar', 'categoria' => 'Quesos', 'unidad' => 'kg', 'precio' => 37000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Queso Philadelphia', 'categoria' => 'Quesos', 'unidad' => 'kg', 'precio' => 28000, 'unidad_compra' => 'gr', 'factor' => 1000],

            // ==================== SALSAS ====================
            ['nombre' => 'Aderezo Bbq', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 7800, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Aderezo Teriyaki', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 15000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Guacamole', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mayonesa', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 13300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mayonesa Fruco', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 19500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Mostaza', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 7200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pasta De Ajo Aderezo', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 16200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Pasta De Tomate', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 12100, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Bbq De La Casa', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 8300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Chipotle', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 23300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Chimichurri', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa De Chocolate Corona', 'categoria' => 'Salsas', 'unidad' => 'Und', 'precio' => 20700, 'unidad_compra' => 'und', 'factor' => 350],
            ['nombre' => 'Salsa De Humo', 'categoria' => 'Salsas', 'unidad' => 'lt', 'precio' => 8100, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Salsa De Maiz', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 24500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa De Soya', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 7300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Heinz Recarga', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 15000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Heinz Tarro', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 11546, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Inglesa', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 7300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa Roja Bary', 'categoria' => 'Salsas', 'unidad' => 'lt', 'precio' => 11700, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Salsa de humo', 'categoria' => 'Salsas', 'unidad' => 'kg', 'precio' => 20883, 'unidad_compra' => 'gr', 'factor' => 1000],

            // ==================== ACEITES Y ABARROTES ====================
            ['nombre' => 'Aceite Garrafon', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'lt', 'precio' => 8400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Aceite Bidón', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'lt', 'precio' => 7400, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Arequipe', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 21000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Azucar morena', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 4200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Azucar blanca', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 3500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Arroz', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 12000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cerezas En Almibar', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 26100, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Chispas De Chocolate', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 28900, 'unidad_compra' => 'gr', 'factor' => 500],
            ['nombre' => 'Chocolate Blanco', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 46300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Cruotones Susanita', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 37600, 'unidad_compra' => 'ml', 'factor' => 250],
            ['nombre' => 'Durazno En Almibar', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 12800, 'unidad_compra' => 'gr', 'factor' => 820],
            ['nombre' => 'Endulzante Splenda', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Frijol Refrito', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 23200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Frutos Secos', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Gelatina Sin Sabor', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 7000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Huevos', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'und', 'precio' => 533, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Insumo Te De Limon', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 19900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Margarina Jdh', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 12500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Miel', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 9100, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Milo', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 31300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Panela', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 6000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Sal Común', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 2600, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Sal Parrilla Tarro', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 22200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Sweet Chilly Sauce', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 29900, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Vainilla', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 8000, 'unidad_compra' => 'und', 'factor' => 1000],
            ['nombre' => 'Vinagre Blanco', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'kg', 'precio' => 1300, 'unidad_compra' => 'ml', 'factor' => 1000],

            // ==================== HARINAS Y PANADERÍA ====================
            ['nombre' => 'Apanado Broster', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 16600, 'unidad_compra' => 'gr', 'factor' => 0],
            ['nombre' => 'Arepa De Tela', 'categoria' => 'Harinas y panadería', 'unidad' => 'unidad', 'precio' => 485, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Arepa De Yuca', 'categoria' => 'Harinas y panadería', 'unidad' => 'unidad', 'precio' => 850, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Fecula De Maiz', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 11500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Empanada Paquete', 'categoria' => 'Harinas y panadería', 'unidad' => 'unidad', 'precio' => 566, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Yuquitas', 'categoria' => 'Harinas y panadería', 'unidad' => 'unidad', 'precio' => 400, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Harina de trigo', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 3200, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Doritos', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 9300, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Nachos Paquete', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 16000, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Masa Para Hojaldre', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 33500, 'unidad_compra' => 'gr', 'factor' => 1000],
            ['nombre' => 'Tortillas', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 830, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Palito De Queso', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 987, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Pan Brioche Brillo', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 1700, 'unidad_compra' => 'gr', 'factor' => 1],
            ['nombre' => 'Pasteles Pollo', 'categoria' => 'Harinas y panadería', 'unidad' => 'kg', 'precio' => 850, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pan Mini Burguer', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => 1100, 'unidad_compra' => 'und', 'factor' => 1],

            // ==================== BEBIDAS ====================
            ['nombre' => 'Absolut 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 45000, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Absolut Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 64097, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Agua Tónica Ginger Beer', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3626, 'unidad_compra' => 'ml', 'factor' => 207],
            ['nombre' => 'Angostura Aromatic', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 75000, 'unidad_compra' => 'ml', 'factor' => 160],
            ['nombre' => 'Angostura Special', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 75000, 'unidad_compra' => 'ml', 'factor' => 160],
            ['nombre' => 'Angostura Orange', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 70000, 'unidad_compra' => 'ml', 'factor' => 160],
            ['nombre' => 'Angostura Cacao', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 70000, 'unidad_compra' => 'ml', 'factor' => 160],
            ['nombre' => 'Antioqueño Azul 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 17105, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Antioqueño Azul Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 31060, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Antioqueño Rojo 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 17105, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Antioqueño Rojo Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 28788, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Antioqueño Verde 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 16160, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Antioqueño Verde Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 25592, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Bombay Sapphire Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 168600, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => "Buchanan's 12 Años 1/2", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 118481, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => "Buchanan's 12 Años Botella", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 202800, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Cachaça Jamel', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 43179, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Campari', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 113800, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Convier De Durazno', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 57000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Convier Triple Sec', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 78990, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Don Julio Reposado Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 287900, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Don Julio Reserva Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 208600, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Gin Dry Shipper', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 24900, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => "Gin Gordon's Pink", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 97100, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => "Gordon's Dry Botella", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 42392, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => "Greenall's Botella", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46376, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => "Hendrick's Botella", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => "Jack Daniel's Apple", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 143100, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => "Jack Daniel's Old No. 7", 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 86821, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Jägermeister Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 76962, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Johnnie Walker Black Label', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 91068, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'La Huerta Blanco', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 47300, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'La Huerta Rosé Varietal', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 47300, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'La Huerta Vino Tinto', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 47300, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Licor de café', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 117500, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Licor De Melón Brisar', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 50400, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Limoncello Villa Massa', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 98568, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Martini Vermouth', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 93300, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Mezcal 400 Conejos', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 185436, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Mezcal Monte Lobos', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 249000, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Mezclador De Toronja', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3792, 'unidad_compra' => 'ml', 'factor' => 207],
            ['nombre' => 'Miel de agave', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46218, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Monin Marshmallow', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 79850, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'Old Parr 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 104990, 'unidad_compra' => 'ml', 'factor' => 500],
            ['nombre' => 'Old Parr Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 127800, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Passport Scotch', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 106000, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'Pisco Quebranta La Botija', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 64184, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Ron Bacardí Añejo Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 48792, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Ron Caldas 8 Años 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 55900, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Ron Caldas 8 Años Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 83400, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Ron Carta De Oro Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 22300, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Ron Cortez Blanco', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 45737, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Ron Cortez Oro', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 52404, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Ron De Coco Malibú', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 42211, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Ron Medellín 3 Años 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 365],
            ['nombre' => 'Ron Medellín 3 Años Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Ron Medellín 8 Años 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 90000, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Ron Medellín 8 Años Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Ron Medellín Dorado', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 32518, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Ron Medellín Dorado 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 86000, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Ron Sailor Jerry Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 67000, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Ron Viejo De Caldas 1/2', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 17582, 'unidad_compra' => 'ml', 'factor' => 375],
            ['nombre' => 'Ron Viejo De Caldas Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 33452, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Sirope De Caramelo', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46990, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Sirope De Durazno', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46990, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Sirope simple', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2.50, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'Sirope De Kiwi', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 35032, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Sirope De Mango', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46990, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Licor 43', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 138950, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Sky Bt', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 40000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Juniper De Coco', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 5.7830, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'Sparkling Tamarindo Mil 976', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 28000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Syrup De Flor De Jamaica', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 15500, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Syrup De Lychee Real', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 35032, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Syrup De Maracuyá', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 15500, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Syrup De Orgeat Fines Call', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46990, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Syrup De Piña Real', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 16663, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Syrup De Uva Blanca', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 15500, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Syrup De Uva Isabella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 15500, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Syrup Simple', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46990, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Tanqueray Rangpur Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 145000, 'unidad_compra' => 'ml', 'factor' => 700],
            ['nombre' => 'Syrup De Uchuva', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 15500, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Tequila Bestia', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 38900, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Tequila Tijuana', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 16965, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'The Famous Grouse Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 16966, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'Tónica 1976 Indi', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 4472, 'unidad_compra' => 'ml', 'factor' => 200],
            ['nombre' => 'Triple Sec Deva', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 21000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Triple Sec Convier', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 9900, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Vino Cabernet Tinto', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 16969, 'unidad_compra' => 'ml', 'factor' => 1],
            ['nombre' => 'Vino J. Alfonso', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Vino Quinta Las Cabras', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 23000, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Vino Rosadela Blanco', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 13658, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Vino Rosadela Rosé', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 13750, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Vino Rosadela Tinto', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 13712, 'unidad_compra' => 'ml', 'factor' => 750],
            ['nombre' => 'Segu Olle Tinto', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 16863, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Segu Olle Rose', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 24000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Segu Olle Blanco', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 24000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Syrup De Jengibre', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 15500, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Vodka Finlandia Botella', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 101000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Vodka Orlikoff', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 25779, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Zumo De Cranberry', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 25210, 'unidad_compra' => 'ml', 'factor' => 1800],
            ['nombre' => 'Zumo De Limón 250 Ml', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 12000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Zumo De Mandarina', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 9000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Zumo De Manzana', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 10000, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Crema De Coco Real', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 46218, 'unidad_compra' => 'ml', 'factor' => 507],
            ['nombre' => 'Zumo De Piña', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'ml', 'factor' => 1000],
            ['nombre' => 'Aguila', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2300, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Aguila Light', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2333, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Club Colombia Dorada', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2933, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Club Colombia Trigo', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3030, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Club Colombia Roja', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3030, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Corona', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3809, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Coronita', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2520, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Heineken', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2682, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pilsen', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2333, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Stella Artois', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3436, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Tres Cordillera', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2645, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => '7up', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1451, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Agua sin gas', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2083, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Agua Gas', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2083, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Colombiana', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1451, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Gatorade', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3250, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Hielo Paquete', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 13000, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Manzana', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1451, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pepsi', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1610, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pepsi Cero', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1917, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Red Bull', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 6388, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Soda 250 Ml', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 817, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Soda 350 Ml', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1917, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Soda Hatsu', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2066, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Te Hatsu', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3501, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Tutifruti', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2000, 'unidad_compra' => 'und', 'factor' => 1000],
            ['nombre' => 'Limonada De Cereza', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3000, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Limonada De Coco', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 3100, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Limonada De Mango Biche', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2300, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Limonada Natural', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa De Frutos Rojos', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2300, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta De Fresa', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1950, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta De Guanabana', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 1950, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta De Mango', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2000, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta De Maracuya', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2400, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta De Mora', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2200, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta Mandarina', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 2300, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa De Coco Café', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 4200, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa De Piña Colada', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 4200, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa De Amazonia', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 4200, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta Sandia', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 4200, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta Pepino', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 0, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Pulpa Fruta Piña Y Maracuya', 'categoria' => 'Bebidas', 'unidad' => 'unidad', 'precio' => 4200, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Tequila para flamear', 'categoria' => 'Bebidas', 'unidad' => 'lt', 'precio' => 20883, 'unidad_compra' => 'ml', 'factor' => 1000],

            // ==================== OTROS ====================
            ['nombre' => 'Tomates secos', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 1, 'unidad_compra' => 'und', 'factor' => 180],
            ['nombre' => 'Salsa de humo', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 20883, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Vinagre de manzana', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Agua', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 1, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Esencia de vainilla', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 8000, 'unidad_compra' => 'und', 'factor' => 500],
            ['nombre' => 'Cobertura de chocolate', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 0, 'unidad_compra' => 'und', 'factor' => 1],
            ['nombre' => 'Galleta oreo', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 8950, 'unidad_compra' => 'und', 'factor' => 400],
            ['nombre' => 'Palos pinchuzo', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 17000, 'unidad_compra' => 'und', 'factor' => 100],
            ['nombre' => 'Salsa de maíz', 'categoria' => 'Otros', 'unidad' => 'kg', 'precio' => 26206, 'unidad_compra' => 'ml', 'factor' => 1000],
        ];

        // Insertar productos
        foreach ($productos as $productoData) {
            $categoria = $categorias[$productoData['categoria']] ?? null;
            $unidadMedida = $unidades[$productoData['unidad']] ?? null;
            $unidadCompra = $unidades[$productoData['unidad_compra']] ?? null;

            if ($categoria && $unidadMedida) {
                Producto::create([
                    'categoria_id' => $categoria->id,
                    'nombre' => $productoData['nombre'],
                    'tipo' => 'insumo',
                    'unidad_medida_id' => $unidadMedida->id,
                    'precio_compra' => $productoData['precio'],
                    'unidad_compra_id' => $unidadCompra ? $unidadCompra->id : null,
                    'factor_conversion' => $productoData['factor'] > 0 ? $productoData['factor'] : 1,
                    'activo' => true,
                    'proveedor_habitual' => null,
                    'notas' => null,
                ]);
            }
        }
    }

    /**
     * Crear categorías si no existen
     */
    private function crearCategorias(): array
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

        $categoriasCreadas = [];

        foreach ($categorias as $nombre) {
            $categoriasCreadas[$nombre] = Categoria::firstOrCreate(
                ['nombre' => $nombre],
                [
                    'nombre' => $nombre,
                    'slug' => Str::slug($nombre),
                    'activo' => true,
                ]
            );
        }

        return $categoriasCreadas;
    }

    /**
     * Obtener o crear unidades de medida
     */
    private function getUnidades(): array
    {
        $unidades = [
            'kg' => ['nombre' => 'Kilogramo', 'abreviatura' => 'kg'],
            'lt' => ['nombre' => 'Litro', 'abreviatura' => 'lt'],
            'und' => ['nombre' => 'Unidad', 'abreviatura' => 'und'],
            'gr' => ['nombre' => 'Gramo', 'abreviatura' => 'gr'],
            'ml' => ['nombre' => 'Mililitro', 'abreviatura' => 'ml'],
        ];

        $unidadesCreadas = [];

        foreach ($unidades as $key => $data) {
            $unidadesCreadas[$key] = \App\Models\Catalog\UnidadMedida::firstOrCreate(
                ['abreviatura' => $data['abreviatura']],
                [
                    'nombre' => $data['nombre'],
                    'activo' => true,
                ]
            );
        }

        return $unidadesCreadas;
    }
}