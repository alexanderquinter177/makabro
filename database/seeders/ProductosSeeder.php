<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\Producto;
use App\Models\Catalog\UnidadMedida;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{       
        public function run()
    {
        $categorias = Categoria::pluck('id', 'nombre')->toArray();
        $unidades = UnidadMedida::pluck('id', 'abreviatura')->toArray();

        if (empty($categorias)) {
            $this->command->error('❌ No hay categorías. Ejecuta primero CategoriasSeeder.');
            return;
        }

        if (empty($unidades)) {
            $this->command->error('❌ No hay unidades de medida. Ejecuta primero CategoriasSeeder.');
            return;
        }

        // ✅ Limpiar la tabla correctamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('productos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $productos = $this->getProductos();

        // ✅ Agrupar productos por categoría ANTES de insertar
        $productosPorCategoria = [];
        foreach ($productos as $productoData) {
            $categoria = $productoData['categoria'];
            if (!isset($productosPorCategoria[$categoria])) {
                $productosPorCategoria[$categoria] = [];
            }
            $productosPorCategoria[$categoria][] = $productoData;
        }

        // ✅ Insertar por categoría para generar códigos secuenciales
        foreach ($productosPorCategoria as $categoriaNombre => $productosDeCategoria) {
            $categoriaId = $categorias[$categoriaNombre] ?? null;
            
            if (!$categoriaId) {
                $this->command->warn("⚠️ Categoría no encontrada: '{$categoriaNombre}'");
                continue;
            }

            // Obtener el último código para esta categoría
            $prefijoTipo = 'IN';
            $codigoCategoria = strtoupper(substr($categoriaNombre, 0, 3));
            
            $ultimo = DB::table('productos')
                        ->where('tipo', 'insumo')
                        ->where('categoria_id', $categoriaId)
                        ->orderBy('id', 'desc')
                        ->first();
            
            $numero = 1;
            if ($ultimo && preg_match('/-(\d{3})$/', $ultimo->codigo, $matches)) {
                $numero = intval($matches[1]) + 1;
            }

            foreach ($productosDeCategoria as $productoData) {
                $unidadCompraId = $unidades[$productoData['unidad']] ?? null;

                if (!$unidadCompraId) {
                    $this->command->warn("⚠️ Unidad no encontrada: '{$productoData['unidad']}' para '{$productoData['nombre']}'");
                    continue;
                }

                $precio = $this->limpiarPrecio($productoData['precio']);
                
                $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);
                $codigo = "{$prefijoTipo}-{$codigoCategoria}-{$numeroFormateado}";

                // ✅ Insertar usando DB::table para evitar eventos
                DB::table('productos')->insert([
                    'categoria_id' => $categoriaId,
                    'nombre' => $productoData['nombre'],
                    'tipo' => 'insumo',
                    'precio_compra' => $precio,
                    'unidad_compra_id' => $unidadCompraId,
                    'activo' => true,
                    'proveedor_habitual' => null,
                    'notas' => null,
                    'codigo' => $codigo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->command->line("   ✅ Creado: {$codigo} - {$productoData['nombre']}");
                $numero++;
            }
        }

        $this->command->info("✅ Todos los productos fueron creados exitosamente!");
    }

    private function limpiarPrecio($precio): float
    {
        if (empty($precio) || $precio === '' || $precio === null) {
            return 0;
        }

        if (is_string($precio)) {
            $precio = str_replace(['$', ' '], '', $precio);
            
            if (str_contains($precio, ',') && str_contains($precio, '.')) {
                $precio = str_replace(',', '', $precio);
            } elseif (str_contains($precio, ',')) {
                $precio = str_replace(',', '.', $precio);
            }
        }

        return floatval($precio);
    }

    /**
     * Obtener todos los productos
     */
    private function getProductos(): array
    {
        return [
            // ===== AVES =====
            
                ['nombre' => 'Alas de pollo', 'categoria' => 'Aves', 'unidad' => 'gr', 'precio' => '16.80'],
                ['nombre' => 'Contramuslo', 'categoria' => 'Aves', 'unidad' => 'gr', 'precio' => '12.90'],
                ['nombre' => 'Pechuga Suprema', 'categoria' => 'Aves', 'unidad' => 'gr', 'precio' => '21.20'],
                ['nombre' => 'Pechuga Filete', 'categoria' => 'Aves', 'unidad' => 'und', 'precio' => '27.50'],
            

            // ===== BEBIDAS =====
            
                ['nombre' => 'Absolut 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '120.00'],
                ['nombre' => 'Absolut Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '85.46'],
                ['nombre' => 'Agua Tónica Ginger Beer', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '17.52'],
                ['nombre' => 'Angostura Aromatic', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '468.75'],
                ['nombre' => 'Angostura Special', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '468.75'],
                ['nombre' => 'Angostura Orange', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '437.50'],
                ['nombre' => 'Angostura Cacao', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '437.50'],
                ['nombre' => 'Antioqueño Azul 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '46.00'],
                ['nombre' => 'Antioqueño Azul Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '41.40'],
                ['nombre' => 'Antioqueño Rojo 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '46.00'],
                ['nombre' => 'Antioqueño Rojo Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '38.00'],
                ['nombre' => 'Antioqueño Verde 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '43.00'],
                ['nombre' => 'Antioqueño Verde Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '37.00'],
                ['nombre' => 'Bombay Sapphire Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '241.00'],
                ['nombre' => "Buchanan's 12 Años 1/2", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '316.00'],
                ['nombre' => "Buchanan's 12 Años Botella", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '270.00'],
                ['nombre' => 'Cachaça Jamel', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '43.00'],
                ['nombre' => 'Campari', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '152.00'],
                ['nombre' => 'Convier De Durazno', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '57.00'],
                ['nombre' => 'Convier Triple Sec', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '78.99'],
                ['nombre' => 'Don Julio Reposado Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '411.00'],
                ['nombre' => 'Don Julio Reserva Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '298.00'],
                ['nombre' => 'Gin Dry Shipper', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '24.90'],
                ['nombre' => "Gin Gordon's Pink", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '139.00'],
                ['nombre' => "Gordon's Dry Botella", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '60.56'],
                ['nombre' => "Greenall's Botella", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '61.83'],
                ['nombre' => "Hendrick's Botella", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => "Jack Daniel's Apple", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '191.00'],
                ['nombre' => "Jack Daniel's Old No. 7", 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '116.00'],
                ['nombre' => 'Jägermeister Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '110.00'],
                ['nombre' => 'Johnnie Walker Black Label', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '130.00'],
                ['nombre' => 'La Huerta Blanco', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '63.00'],
                ['nombre' => 'La Huerta Rosé Varietal', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '63.00'],
                ['nombre' => 'La Huerta Vino Tinto', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '63.00'],
                ['nombre' => 'Licor de café', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '157.00'],
                ['nombre' => 'Licor De Melón Brisar', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '67.00'],
                ['nombre' => 'Limoncello Villa Massa', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '141.00'],
                ['nombre' => 'Martini Vermouth', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '124.00'],
                ['nombre' => 'Mezcal 400 Conejos', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '247.00'],
                ['nombre' => 'Mezcal Monte Lobos', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '332.00'],
                ['nombre' => 'Mezclador De Toronja', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '18.32'],
                ['nombre' => 'Miel de agave', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '91.16'],
                ['nombre' => 'Monin Marshmallow', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '79.85'],
                ['nombre' => 'Old Parr 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '210.00'],
                ['nombre' => 'Old Parr Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '170.00'],
                ['nombre' => 'Passport Scotch', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '106.00'],
                ['nombre' => 'Pisco Quebranta La Botija', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '86.00'],
                ['nombre' => 'Ron Bacardí Añejo Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '69.70'],
                ['nombre' => 'Ron Caldas 8 Años 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '149.00'],
                ['nombre' => 'Ron Caldas 8 Años Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '111.20'],
                ['nombre' => 'Ron Carta De Oro Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '22.30'],
                ['nombre' => 'Ron Cortez Blanco', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '45.74'],
                ['nombre' => 'Ron Cortez Oro', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '52.40'],
                ['nombre' => 'Ron De Coco Malibú', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '56.28'],
                ['nombre' => 'Ron Medellín 3 Años 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => 'Ron Medellín 3 Años Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => 'Ron Medellín 8 Años 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '240.00'],
                ['nombre' => 'Ron Medellín 8 Años Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => 'Ron Medellín Dorado', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '43.36'],
                ['nombre' => 'Ron Medellín Dorado 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '229.33'],
                ['nombre' => 'Ron Sailor Jerry Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '95.71'],
                ['nombre' => 'Ron Viejo De Caldas 1/2', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '46.89'],
                ['nombre' => 'Ron Viejo De Caldas Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '44.60'],
                ['nombre' => 'Sirope De Caramelo', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '92.68'],
                ['nombre' => 'Sirope De Durazno', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '92.68'],
                ['nombre' => 'Sirope simple', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => 'Sirope De Kiwi', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '69.10'],
                ['nombre' => 'Sirope De Mango', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '92.68'],
                ['nombre' => 'Licor 43', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '185.00'],
                ['nombre' => 'Sky Bt', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '40.00'],
                ['nombre' => 'Juniper De Coco', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '5.78'],
                ['nombre' => 'Sparkling Tamarindo Mil 976', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '28.00'],
                ['nombre' => 'Syrup De Flor De Jamaica', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '15.50'],
                ['nombre' => 'Syrup De Lychee Real', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '69.10'],
                ['nombre' => 'Syrup De Maracuyá', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '15.50'],
                ['nombre' => 'Syrup De Orgeat Fines Call', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '92.68'],
                ['nombre' => 'Syrup De Piña Real', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '32.87'],
                ['nombre' => 'Syrup De Uva Blanca', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '15.50'],
                ['nombre' => 'Syrup De Uva Isabella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '15.50'],
                ['nombre' => 'Syrup Simple', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '92.68'],
                ['nombre' => 'Tanqueray Rangpur Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '207.14'],
                ['nombre' => 'Syrup De Uchuva', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '15.50'],
                ['nombre' => 'Tequila Bestia', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '38.90'],
                ['nombre' => 'Tequila Tijuana', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '16.97'],
                ['nombre' => 'The Famous Grouse Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '16.97'],
                ['nombre' => 'Tónica 1976 Indi', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '22.36'],
                ['nombre' => 'Triple Sec Deva', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '21.00'],
                ['nombre' => 'Triple Sec Convier', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '9.90'],
                ['nombre' => 'Vino Cabernet Tinto', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '16.97'],
                ['nombre' => 'Vino J. Alfonso', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => 'Vino Quinta Las Cabras', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '31.00'],
                ['nombre' => 'Vino Rosadela Blanco', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '18.21'],
                ['nombre' => 'Vino Rosadela Rosé', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '18.33'],
                ['nombre' => 'Vino Rosadela Tinto', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '18.28'],
                ['nombre' => 'Segu Olle Tinto', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '16.86'],
                ['nombre' => 'Segu Olle Rose', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '24.00'],
                ['nombre' => 'Segu Olle Blanco', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '24.00'],
                ['nombre' => 'Syrup De Jengibre', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '15.50'],
                ['nombre' => 'Vodka Finlandia Botella', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '101.00'],
                ['nombre' => 'Vodka Orlikoff', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '25.78'],
                ['nombre' => 'zumo De Cranberry', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '14.01'],
                ['nombre' => 'zumo De Limón 250 Ml', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '12.00'],
                ['nombre' => 'zumo De Mandarina', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '9.00'],
                ['nombre' => 'zumo De Manzana', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '10.00'],
                ['nombre' => 'Crema De Coco Real', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '91.16'],
                ['nombre' => 'zumo De Piña', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '0.00'],
                ['nombre' => 'Aguila', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2300.00'],
                ['nombre' => 'Aguila Light', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2333.00'],
                ['nombre' => 'Club Colombia Dorada', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2933.00'],
                ['nombre' => 'Club Colombia Trigo', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3030.00'],
                ['nombre' => 'Club Colombia Roja', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3030.00'],
                ['nombre' => 'Corona', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3809.00'],
                ['nombre' => 'Coronita', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2520.00'],
                ['nombre' => 'Heineken', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2682.00'],
                ['nombre' => 'Pilsen', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2333.00'],
                ['nombre' => 'Stella Artois', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3436.00'],
                ['nombre' => 'Tres Cordillera', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2645.00'],
                ['nombre' => '7up', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1451.00'],
                ['nombre' => '7up 1,5', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3.00'],
                ['nombre' => 'Agua sin gas', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2083.00'],
                ['nombre' => 'Agua Gas', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2083.00'],
                ['nombre' => 'Colombiana', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1451.00'],
                ['nombre' => 'Gatorade', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3250.00'],
                ['nombre' => 'Hielo Paquete', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '13000.00'],
                ['nombre' => 'Manzana', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1451.00'],
                ['nombre' => 'Pepsi', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1610.00'],
                ['nombre' => 'Pepsi Cero', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1917.00'],
                ['nombre' => 'Red Bull', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '6388.00'],
                ['nombre' => 'Soda 250 Ml', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '817.00'],
                ['nombre' => 'Soda 350 Ml', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1917.00'],
                ['nombre' => 'Soda Hatsu', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2066.00'],
                ['nombre' => 'Te Hatsu', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3501.00'],
                ['nombre' => 'Tutifruti', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2.00'],
                ['nombre' => 'Limonada De Cereza', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3000.00'],
                ['nombre' => 'Limonada De Coco', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '3100.00'],
                ['nombre' => 'Limonada De Mango Biche', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2300.00'],
                ['nombre' => 'Limonada Natural', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '0.00'],
                ['nombre' => 'Pulpa De Frutos Rojos', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2300.00'],
                ['nombre' => 'Pulpa Fruta De Fresa', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1950.00'],
                ['nombre' => 'Pulpa Fruta De Guanabana', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1950.00'],
                ['nombre' => 'Pulpa Fruta De Mango', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2000.00'],
                ['nombre' => 'Pulpa Fruta De Maracuya', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2400.00'],
                ['nombre' => 'Pulpa Fruta De Mora', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2200.00'],
                ['nombre' => 'Pulpa Fruta Mandarina', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '2300.00'],
                ['nombre' => 'Pulpa De Coco Café', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '4200.00'],
                ['nombre' => 'Pulpa De Piña Colada', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '4200.00'],
                ['nombre' => 'Pulpa De Amazonia', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '4200.00'],
                ['nombre' => 'Pulpa Fruta Sandia', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '4200.00'],
                ['nombre' => 'Pulpa Fruta Pepino', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '0.00'],
                ['nombre' => 'Pulpa Fruta Piña Y Maracuya', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '4200.00'],
                ['nombre' => 'Agua', 'categoria' => 'Bebidas', 'unidad' => 'und', 'precio' => '1.00'],
                ['nombre' => 'Tequila para flamear', 'categoria' => 'Bebidas', 'unidad' => 'ml', 'precio' => '20.88'],
            

            // ===== CARNES =====
            
                ['nombre' => 'Asado De Tira', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '21.50'],
                ['nombre' => 'Bondiola De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '20.00'],
                ['nombre' => 'Cañón De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '0.00'],
                ['nombre' => 'Chicharron - Tocino', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '23.00'],
                ['nombre' => 'Chorizo De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '3423.00'],
                ['nombre' => 'Chorizo Argentino', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '3423.00'],
                ['nombre' => 'Chorizo Burguer', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '2000.00'],
                ['nombre' => 'Costichi', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '26.98'],
                ['nombre' => 'Costilla San Luis', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '27.48'],
                ['nombre' => 'Desmechada Copete', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '67.00'],
                ['nombre' => 'Morrillo', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '32.60'],
                ['nombre' => 'Espetada Al Pastor', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '0.00'],
                ['nombre' => 'Jamón', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => ''],
                ['nombre' => 'Medallones de Solomito', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '80.90'],
                ['nombre' => 'Morcilla Cóctelera', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '589.00'],
                ['nombre' => 'Pecho De Res', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '30.80'],
                ['nombre' => 'Pierna De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '24.50'],
                ['nombre' => 'Punta De Anca De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '6600.00'],
                ['nombre' => 'Punta Hamburguesa (150g)', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '32.50'],
                ['nombre' => 'Solomito De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '6400.00'],
                ['nombre' => 'Tocineta', 'categoria' => 'Carnes', 'unidad' => 'gr', 'precio' => '1703.13'],
                ['nombre' => 'Tomahawk De Cerdo', 'categoria' => 'Carnes', 'unidad' => 'und', 'precio' => '12600.00'],
                            

            // ==================== ACEITES Y ABARROTES ====================
        
            ['nombre' => 'Aceite Garrafon', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'ml', 'precio' => '2.80'],
            ['nombre' => 'Aceite Bidón', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'ml', 'precio' => '3.70'],
            ['nombre' => 'Arequipe', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '21.00'],
            ['nombre' => 'Azucar morena', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '4.20'],
            ['nombre' => 'Azucar blanca', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '3.50'],
            ['nombre' => 'Arroz', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '12.00'],
            ['nombre' => 'Cerezas En Almibar', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '26.10'],
            ['nombre' => 'Chispas De Chocolate', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '57.80'],
            ['nombre' => 'Chocolate Blanco', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '46.30'],
            ['nombre' => 'Cruotones Susanita', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'und', 'precio' => '150.40'],
            ['nombre' => 'Durazno En Almibar', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '15.61'],
            ['nombre' => 'Endulzante Splenda', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Frijol Refrito', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '23.20'],
            ['nombre' => 'Frutos Secos', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Gelatina Sin Sabor', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '7.00'],
            ['nombre' => 'Huevos', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'und', 'precio' => '533.00'],
            ['nombre' => 'Insumo Te De Limon', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '19.90'],
            ['nombre' => 'Margarina Jdh', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '12.50'],
            ['nombre' => 'Miel', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '9.10'],
            ['nombre' => 'Milo', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '31.30'],
            ['nombre' => 'Panela', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '6.00'],
            ['nombre' => 'Sal Común', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '2.60'],
            ['nombre' => 'Sal Parrilla Tarro', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '22.20'],
            ['nombre' => 'Sweet Chilly Sauce', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'gr', 'precio' => '29.90'],
            ['nombre' => 'Vainilla', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'ml', 'precio' => '8.00'],
            ['nombre' => 'Vinagre Blanco', 'categoria' => 'Aceites y abarrotes', 'unidad' => 'ml', 'precio' => '1.30'],
        


                // ===== ESPECIAS =====
    
            ['nombre' => 'Ajinomoto', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '17.00'],
            ['nombre' => 'Ajo En Polvo', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '24.00'],
            ['nombre' => 'Ajonjoli', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '25.50'],
            ['nombre' => 'Albahaca Seca', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '4.00'],
            ['nombre' => 'Almendra', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '24.00'],
            ['nombre' => 'Arandanos', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '40.00'],
            ['nombre' => 'Bicarbonato', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Canela Molida', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '21.00'],
            ['nombre' => 'Cebolla En Polvo', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '34.00'],
            ['nombre' => 'Clavo De Olor', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '54.00'],
            ['nombre' => 'Cocoa En Polvo', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Colorante Liquido Amarillo', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '5.40'],
            ['nombre' => 'Colorante Liquido Rojo', 'categoria' => 'Especias', 'unidad' => 'und', 'precio' => '5.40'],
            ['nombre' => 'Comino', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '5.40'],
            ['nombre' => 'Coriandro', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '36.00'],
            ['nombre' => 'Curcuma', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '29.00'],
            ['nombre' => 'Curry', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Finas Hierbas Por 500 Gramos', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '72.00'],
            ['nombre' => 'Flor De Jamaica', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '101.00'],
            ['nombre' => 'Laurel En Polvo', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Mani Simple', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '78.40'],
            ['nombre' => 'Mix De Flores', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '15.40'],
            ['nombre' => 'Nuez Del Brasil', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '62.00'],
            ['nombre' => 'Oregano En Polvo', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '62.00'],
            ['nombre' => 'Oregano Escamas', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '32.00'],
            ['nombre' => 'Panko', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '17.50'],
            ['nombre' => 'Paprika', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '68.00'],
            ['nombre' => 'Paprika Con Sal', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '22.00'],
            ['nombre' => 'Pimienta Cayena', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '250.00'],
            ['nombre' => 'Pimienta Molida', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '27.00'],
            ['nombre' => 'Pimienta Negra', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '34.00'],
            ['nombre' => 'Sazon Completo Badia', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '43.80'],
            ['nombre' => 'Tajín', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '68.30'],
            ['nombre' => 'Tomillo Escamas', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '14.00'],
            ['nombre' => 'Tomillo Molido', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '32.00'],
            ['nombre' => 'Achiote', 'categoria' => 'Especias', 'unidad' => 'gr', 'precio' => '1.00'],
            ['nombre' => 'Salsa de chocolate', 'categoria' => 'Especias', 'unidad' => 'ml', 'precio' => '22.66'],
        

        // ===== FRUTAS =====
    
            ['nombre' => 'Mazorca', 'categoria' => 'Frutas y verduras', 'unidad' => 'und', 'precio' => '7.00'],
            ['nombre' => 'batata', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '20.00'],
            ['nombre' => 'Kiwi', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '17.50'],
        

        // ===== FRUTAS Y VERDURAS =====
        
            ['nombre' => 'Aguacate Hass', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '8.50'],
            ['nombre' => 'Aguacate Papelillo-Collin', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '12.50'],
            ['nombre' => 'Aji Dulce', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '7.00'],
            ['nombre' => 'Aji Picante', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '25.00'],
            ['nombre' => 'Ajo Pelado', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '10.00'],
            ['nombre' => 'Albahaca Fresca', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '16.80'],
            ['nombre' => 'Apio', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '1.90'],
            ['nombre' => 'Brotes', 'categoria' => 'Frutas y verduras', 'unidad' => 'und', 'precio' => '300.00'],
            ['nombre' => 'Chile Guajillo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '857.10'],
            ['nombre' => 'Cebolla Blanca', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '9.10'],
            ['nombre' => 'Cebolla Puerro', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.00'],
            ['nombre' => 'Cebolla Rama', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '4.20'],
            ['nombre' => 'Cebolla Roja', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '11.50'],
            ['nombre' => 'Cebollin Criollo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '12.90'],
            ['nombre' => 'Champiñon Entero', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '30.00'],
            ['nombre' => 'Cilantro', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.20'],
            ['nombre' => 'Durazno', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '9.00'],
            ['nombre' => 'Fresa', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '4.60'],
            ['nombre' => 'Gulupa', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.20'],
            ['nombre' => 'Hierbabuena', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '18.00'],
            ['nombre' => 'Hojas De Tamal', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '7.80'],
            ['nombre' => 'Jalapeño Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '23.00'],
            ['nombre' => 'Jengibre', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '14.00'],
            ['nombre' => 'Laurel En Hojas', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '46.00'],
            ['nombre' => 'Lechuga Cogollo Europeo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '6.60'],
            ['nombre' => 'Lechuga Crespa Mix Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '10.00'],
            ['nombre' => 'Lechuga Crespa Morada', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '10.00'],
            ['nombre' => 'Limon Tahiti', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '4.60'],
            ['nombre' => 'Limonaria', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '8.50'],
            ['nombre' => 'Maiz Dulce', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '14.70'],
            ['nombre' => 'Mango Tommy', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '8.40'],
            ['nombre' => 'Mezclum', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '62.30'],
            ['nombre' => 'Mandarina', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.90'],
            ['nombre' => 'Manzana Roja', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '13.90'],
            ['nombre' => 'Manzana Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '12.30'],
            ['nombre' => 'Maracuya', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '7.80'],
            ['nombre' => 'Mora', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '8.33'],
            ['nombre' => 'Naranja', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '3.40'],
            ['nombre' => 'Papa Francesa', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '12.60'],
            ['nombre' => 'Papa Capira', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '2.60'],
            ['nombre' => 'Papa Criolla Media', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.20'],
            ['nombre' => 'Papa Criolla Tronco', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.20'],
            ['nombre' => 'Patacon Paquete Por 40 Unidades', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '270.00'],
            ['nombre' => 'Pepino Cohombro', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '2.00'],
            ['nombre' => 'Perejil Crespo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '16.80'],
            ['nombre' => 'Pimenton Rojo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '4.20'],
            ['nombre' => 'Piña oro miel', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '3.70'],
            ['nombre' => 'Plátano Maduro', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '3.60'],
            ['nombre' => 'Plátano Verde', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '3.60'],
            ['nombre' => 'Raíz China', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '2.20'],
            ['nombre' => 'Rocotto', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '9.70'],
            ['nombre' => 'Romero', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '20.00'],
            ['nombre' => 'Tamarindo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '16.90'],
            ['nombre' => 'Tomate Cherry', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '14.20'],
            ['nombre' => 'Tomate chonto', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.70'],
            ['nombre' => 'Tomate Larga Vida', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '6.50'],
            ['nombre' => 'Toronja', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '11.60'],
            ['nombre' => 'Uchuva', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '3.30'],
            ['nombre' => 'Zanahoria', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '3.20'],
            ['nombre' => 'Uva Blanca', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '36.00'],
            ['nombre' => 'Zuquini Verde Y Amarillo', 'categoria' => 'Frutas y verduras', 'unidad' => 'gr', 'precio' => '5.00'],
        

        // ===== HARINAS Y PANADERÍA =====
        
            ['nombre' => 'Apanado Broster', 'categoria' => 'Harinas y panadería', 'unidad' => 'gr', 'precio' => ''],
            ['nombre' => 'Arepa De Tela', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '485.00'],
            ['nombre' => 'Arepa De Yuca', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '850.00'],
            ['nombre' => 'Fecula De Maiz', 'categoria' => 'Harinas y panadería', 'unidad' => 'gr', 'precio' => '11.50'],
            ['nombre' => 'Empanada Paquete', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '566.00'],
            ['nombre' => 'Yuquitas', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '400.00'],
            ['nombre' => 'Harina de trigo', 'categoria' => 'Harinas y panadería', 'unidad' => 'gr', 'precio' => '3.20'],
            ['nombre' => 'Doritos', 'categoria' => 'Harinas y panadería', 'unidad' => 'gr', 'precio' => '9.30'],
            ['nombre' => 'Nachos Paquete', 'categoria' => 'Harinas y panadería', 'unidad' => 'gr', 'precio' => '16.00'],
            ['nombre' => 'Masa Para Hojaldre', 'categoria' => 'Harinas y panadería', 'unidad' => 'gr', 'precio' => '33.50'],
            ['nombre' => 'Tortillas', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '830.00'],
            ['nombre' => 'Palito De Queso', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '987.00'],
            ['nombre' => 'Pan Brioche Brillo', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '1700.00'],
            ['nombre' => 'Pasteles Pollo', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '850.00'],
            ['nombre' => 'Pan Mini Burguer', 'categoria' => 'Harinas y panadería', 'unidad' => 'und', 'precio' => '1100.00'],
        

        // ===== LÁCTEOS =====
    
            ['nombre' => 'Crema Agria', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '5.08'],
            ['nombre' => 'Crema De Leche Colanta', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Crema De Leche de vida', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '19.00'],
            ['nombre' => 'Crema Pastelera', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '13.00'],
            ['nombre' => 'Leche Entera', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '4.11'],
            ['nombre' => 'Leche Condensada', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '36.40'],
            ['nombre' => 'Mantequilla', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '66.00'],
            ['nombre' => 'Queso crema', 'categoria' => 'Lácteos', 'unidad' => 'gr', 'precio' => '20.25'],
        

        // ===== OTROS =====
    
            ['nombre' => 'Tomates secos', 'categoria' => 'Otros', 'unidad' => 'und', 'precio' => '0.01'],
            ['nombre' => 'Salsa de humo', 'categoria' => 'Otros', 'unidad' => 'und', 'precio' => ''],
            ['nombre' => 'Vinagre de manzana', 'categoria' => 'Otros', 'unidad' => 'und', 'precio' => ''],
            ['nombre' => 'Esencia de vainilla', 'categoria' => 'Otros', 'unidad' => 'und', 'precio' => '16.00'],
            ['nombre' => 'Cobertura de chocolate', 'categoria' => 'Otros', 'unidad' => 'gr', 'precio' => ''],
            ['nombre' => 'Galleta oreo', 'categoria' => 'Otros', 'unidad' => 'gr', 'precio' => '22.38'],
            ['nombre' => 'Palos pinchuzo', 'categoria' => 'Otros', 'unidad' => 'und', 'precio' => '170.00'],
            ['nombre' => 'Salsa de maíz', 'categoria' => 'Otros', 'unidad' => 'ml', 'precio' => '26.21'],
        
        // ===== QUESOS =====
        
            ['nombre' => 'Queso Azul', 'categoria' => 'Quesos', 'unidad' => 'gr', 'precio' => '660.00'],
            ['nombre' => 'Queso Cheddar Tajado', 'categoria' => 'Quesos', 'unidad' => 'und', 'precio' => '654.00'],
            ['nombre' => 'Queso Gouda', 'categoria' => 'Quesos', 'unidad' => 'und', 'precio' => '830.00'],
            ['nombre' => 'Queso Entero', 'categoria' => 'Quesos', 'unidad' => 'gr', 'precio' => '20.90'],
            ['nombre' => 'Queso Mozzarella Tajado', 'categoria' => 'Quesos', 'unidad' => 'und', 'precio' => '583.00'],
            ['nombre' => 'Queso Parmesano', 'categoria' => 'Quesos', 'unidad' => 'gr', 'precio' => '35.00'],
            ['nombre' => 'Queso asar', 'categoria' => 'Quesos', 'unidad' => 'gr', 'precio' => '37.00'],
            ['nombre' => 'Queso Philadelphia', 'categoria' => 'Quesos', 'unidad' => 'gr', 'precio' => '28.00'],
        

        // ===== RENDIMIENTO =====
        
            ['nombre' => 'Rend bondiola de cerdo', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '28.57'],
            ['nombre' => 'Rend zumo de Naranja', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '8.50'],
            ['nombre' => 'Rend zumo de Maracuya', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '19.50'],
            ['nombre' => 'Rend zumo de limón', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '12.77'],
            ['nombre' => 'Rend Cilantro', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '6.50'],
            ['nombre' => 'Rend mango tommy', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '14.00'],
            ['nombre' => 'Rend aguacate hass', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '15.32'],
            ['nombre' => 'Rend piña oro miel', 'categoria' => 'rendimiento', 'unidad' => 'gr', 'precio' => '4.50'],
        

        // ===== SALSAS =====
        
            ['nombre' => 'Aderezo Bbq', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '7.80'],
            ['nombre' => 'Aderezo Teriyaki', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '15.00'],
            ['nombre' => 'Guacamole', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Mayonesa', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '13.30'],
            ['nombre' => 'Mayonesa Fruco', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '19.50'],
            ['nombre' => 'Mostaza', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '7.20'],
            ['nombre' => 'Pasta De Ajo Aderezo', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '16.20'],
            ['nombre' => 'Pasta De Tomate', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '12.10'],
            ['nombre' => 'Salsa Bbq De La Casa', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '8.30'],
            ['nombre' => 'Salsa Chipotle', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '23.30'],
            ['nombre' => 'Salsa Chimichurri', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '0.00'],
            ['nombre' => 'Salsa De Chocolate Corona', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '59.14'],
            ['nombre' => 'Salsa De Humo', 'categoria' => 'Salsas', 'unidad' => 'ml', 'precio' => '8.10'],
            ['nombre' => 'Salsa De Maiz', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '24.50'],
            ['nombre' => 'Salsa De Soya', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '7.30'],
            ['nombre' => 'Salsa Heinz Recarga', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '15.00'],
            ['nombre' => 'Salsa Heinz Tarro', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '11.55'],
            ['nombre' => 'Salsa Inglesa', 'categoria' => 'Salsas', 'unidad' => 'gr', 'precio' => '7.30'],
            ['nombre' => 'Salsa Roja Bary', 'categoria' => 'Salsas', 'unidad' => 'ml', 'precio' => '11.70'],        

        ];
    }
}