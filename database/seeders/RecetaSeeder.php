<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Catalog\Producto;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\UnidadMedida;

class RecetaSeeder extends Seeder
{
     public function run(): void
    {
        $this->command->info('📦 Cargando Recetas...');


        $categoriaRecetas = Categoria::firstOrCreate(
            ['nombre' => 'Recetas'],
            [
                'nombre' => 'Recetas',
                'slug' => \Illuminate\Support\Str::slug('Recetas'),
                'descripcion' => 'Recetas para platos',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $unidadGr = UnidadMedida::where('abreviatura', 'und')->first();
        $unidadCompraId = $unidadGr ? $unidadGr->id : 4;

        $this->command->info("📂 Categoría Recetas ID: {$categoriaRecetas->id}");

        // ========================================
        // 1. AREPAS CRIOLLAS CON PULLED PORK
        // ========================================
        $this->crearReceta('AREPAS CRIOLLAS CON PULLED PORK', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Arepa de yuca', 'cantidad' => 2],
            ['nombre' => 'Sub pulled pork', 'cantidad' => 60],
            ['nombre' => 'Rend cilantro', 'cantidad' => 5],
            ['nombre' => 'Sub guacamole', 'cantidad' => 25],
            ['nombre' => 'Sub sour makabra', 'cantidad' => 15],
            ['nombre' => 'Sub pico de gallo', 'cantidad' => 20],
            ['nombre' => 'Salsa de maíz', 'cantidad' => 5],
            ['nombre' => 'Hojas de tamal', 'cantidad' => 5],
        ]);

        // ========================================
        // 2. PAPAS VIKINGAS
        // ========================================
        $this->crearReceta('PAPAS VIKINGAS', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Papa Francesa', 'cantidad' => 240],
            ['nombre' => 'Aji Dulce', 'cantidad' => 5],
            ['nombre' => 'Sub Bbq de tamarindo y ron', 'cantidad' => 20],
            ['nombre' => 'Rend cilantro', 'cantidad' => 5],
            ['nombre' => 'Sub mermelada de tocineta', 'cantidad' => 20],
            ['nombre' => 'Desmechada copete', 'cantidad' => 40],
            ['nombre' => 'Frijol refrito', 'cantidad' => 30],
            ['nombre' => 'Sub guacamole', 'cantidad' => 30],
            ['nombre' => 'Sub hogao', 'cantidad' => 20],
            ['nombre' => 'Sub pico de gallo', 'cantidad' => 20],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Sub mayo sweet chilli', 'cantidad' => 30],
            ['nombre' => 'Sub sour makabra', 'cantidad' => 40],
        ]);

        // ========================================
        // 3. QUESOS PARRILLADOS
        // ========================================
        $this->crearReceta('QUESOS PARRILLADOS', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Queso asar', 'cantidad' => 150],
            ['nombre' => 'Sub chutney de mango', 'cantidad' => 40],
            ['nombre' => 'Sub salsa de panela', 'cantidad' => 20],
            ['nombre' => 'Brotes', 'cantidad' => 2],
        ]);

        // ========================================
        // 4. CROCANTES CRIOLLOS
        // ========================================
        $this->crearReceta('CROCANTES CRIOLLOS', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Empanada paquete', 'cantidad' => 3],
            ['nombre' => 'Palito de queso', 'cantidad' => 3],
            ['nombre' => 'Pasteles Pollo', 'cantidad' => 3],
            ['nombre' => 'Sub guacamole', 'cantidad' => 30],
            ['nombre' => 'Sub chutney de mango', 'cantidad' => 30],
        ]);

        // ========================================
        // 5. CEVICHE DE CHICHARRÓN
        // ========================================
        $this->crearReceta('CEVICHE DE CHICHARRÓN', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub chicharrón toteado', 'cantidad' => 200],
            ['nombre' => 'Sub salsa de gulupa', 'cantidad' => 60],
            ['nombre' => 'Limon tahiti', 'cantidad' => 60],
            ['nombre' => 'Sub cebolla encurtida', 'cantidad' => 30],
            ['nombre' => 'Cilantro', 'cantidad' => 5],
            ['nombre' => 'Aji Dulce', 'cantidad' => 2],
            ['nombre' => 'Sal Común', 'cantidad' => 1],
            ['nombre' => 'Sub chips de plátano', 'cantidad' => 4],
            ['nombre' => 'Pimienta negra', 'cantidad' => 0.5],
            ['nombre' => 'Rend cilantro', 'cantidad' => 2],
        ]);

        // ========================================
        // 6. TOCINOS CROCANTES GLASEADOS
        // ========================================
        $this->crearReceta('TOCINOS CROCANTES GLASEADOS', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub chicharrón toteado', 'cantidad' => 200],
            ['nombre' => 'Sub guacamole', 'cantidad' => 100],
            ['nombre' => 'Sub salsa de panela', 'cantidad' => 50],
            ['nombre' => 'Sub cebolla encurtida', 'cantidad' => 10],
            ['nombre' => 'Batata', 'cantidad' => 20],
            ['nombre' => 'Papa criolla media', 'cantidad' => 120],
        ]);


                // ========================================
        // 7. BURGER PULLED PORK
        // ========================================
        $this->crearReceta('BURGER PULLED PORK', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Tocineta', 'cantidad' => 1.35],
            ['nombre' => 'Queso cheddar tajado', 'cantidad' => 2],
            ['nombre' => 'Sub salsa hamburguesera', 'cantidad' => 30],
            ['nombre' => 'Sub carne de hamburguesa', 'cantidad' => 150],
            ['nombre' => 'Sub pulled pork', 'cantidad' => 30],
            ['nombre' => 'Sub Bbq de tamarindo y ron', 'cantidad' => 20],
            ['nombre' => 'Sub cebolla caramelizada', 'cantidad' => 20],
            ['nombre' => 'Lechuga cogollo europeo', 'cantidad' => 30],
            ['nombre' => 'Tomate chonto', 'cantidad' => 30],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Pan brioche brillo', 'cantidad' => 1],
        ]);

        // ========================================
        // 8. BURGER MAKABRA
        // ========================================
        $this->crearReceta('BURGER MAKABRA', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Pan brioche brillo', 'cantidad' => 1],
            ['nombre' => 'Sub carne de hamburguesa', 'cantidad' => 150],
            ['nombre' => 'Sub mayonesa chimichurrí', 'cantidad' => 40],
            ['nombre' => 'Sub cebolla caramelizada', 'cantidad' => 20],
            ['nombre' => 'Chorizo de cerdo', 'cantidad' => 1],
            ['nombre' => 'Queso Gouda', 'cantidad' => 2],
            ['nombre' => 'Tomate chonto', 'cantidad' => 30],
            ['nombre' => 'Lechuga cogollo europeo', 'cantidad' => 10],
            ['nombre' => 'Sub chimichurri', 'cantidad' => 60],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
        ]);

        // ========================================
        // 9. BURGER POLLO CRISPY
        // ========================================
        $this->crearReceta('BURGER POLLO CRISPY', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Pechuga filete', 'cantidad' => 125],
            ['nombre' => 'Sweet Chilly Sauce', 'cantidad' => 30],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 30],
            ['nombre' => 'Sub mayo sweet chilli', 'cantidad' => 30],
            ['nombre' => 'Sub salsa hamburguesera', 'cantidad' => 40],
            ['nombre' => 'Sub harina compuesta', 'cantidad' => 50],
            ['nombre' => 'Sub pepinillo encurtido', 'cantidad' => 15],
            ['nombre' => 'Lechuga cogollo europeo', 'cantidad' => 30],
            ['nombre' => 'Tomate chonto', 'cantidad' => 40],
            ['nombre' => 'Sub mermelada de tocineta', 'cantidad' => 30],
            ['nombre' => 'Queso philadelphia', 'cantidad' => 30],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Pan brioche brillo', 'cantidad' => 1],
        ]);

        // ========================================
        // 10. BURGER FILADELFIA
        // ========================================
        $this->crearReceta('BURGER FILADELFIA', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub carne de hamburguesa', 'cantidad' => 150],
            ['nombre' => 'Sub salsa de panela', 'cantidad' => 30],
            ['nombre' => 'Sub salsa hamburguesera', 'cantidad' => 30],
            ['nombre' => 'Plátano Maduro', 'cantidad' => 80],
            ['nombre' => 'Queso philadelphia', 'cantidad' => 50],
            ['nombre' => 'Lechuga cogollo europeo', 'cantidad' => 30],
            ['nombre' => 'Sub cebolla crispy', 'cantidad' => 60],
            ['nombre' => 'Tomate chonto', 'cantidad' => 30],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Tocineta', 'cantidad' => 1],
        ]);

                // ========================================
        // 11. ESPETADA AL PASTOR
        // ========================================
        $this->crearReceta('ESPETADA AL PASTOR', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub carne al pastor', 'cantidad' => 200],
            ['nombre' => 'Rend piña oro miel', 'cantidad' => 100],
            ['nombre' => 'Cebolla roja', 'cantidad' => 30],
            ['nombre' => 'Pimenton rojo', 'cantidad' => 30],
            ['nombre' => 'Sub cebolla encurtida', 'cantidad' => 20],
            ['nombre' => 'Tortillas', 'cantidad' => 6],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 3],
            ['nombre' => 'Sub sour makabra', 'cantidad' => 20],
            ['nombre' => 'Sub guacamole', 'cantidad' => 20],
            ['nombre' => 'Sub salsa tatemada', 'cantidad' => 20],
        ]);

        // ========================================
        // 12. CARNITAS GRATINADAS
        // ========================================
        $this->crearReceta('CARNITAS GRATINADAS', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Solomito de cerdo', 'cantidad' => 0.5],
            ['nombre' => 'Sub chicharrón toteado', 'cantidad' => 100],
            ['nombre' => 'Sub salsa tatemada', 'cantidad' => 40],
            ['nombre' => 'Sub sour makabra', 'cantidad' => 40],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 2],
            ['nombre' => 'Tortillas', 'cantidad' => 6],
            ['nombre' => 'Sub guacamole', 'cantidad' => 20],
            ['nombre' => 'Sub pico de gallo', 'cantidad' => 20],
        ]);

        // ========================================
        // 13. CROQUETAS PULLED PORK
        // ========================================
        $this->crearReceta('CROQUETAS PULLED PORK', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub croquetas pulled pork', 'cantidad' => 4],
            ['nombre' => 'Sub mayo sweet chilli', 'cantidad' => 30],
            ['nombre' => 'Rend cilantro', 'cantidad' => 2],
        ]);

        // ========================================
        // 14. ALAS X 12
        // ========================================
        $this->crearReceta('ALAS X 12', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub harina compuesta', 'cantidad' => 50],
            ['nombre' => 'Sub alas de pollo salmuera', 'cantidad' => 500],
            ['nombre' => 'Queso parmesano', 'cantidad' => 5],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Sub Bbq de tamarindo y ron', 'cantidad' => 60],
            ['nombre' => 'Sweet Chilly Sauce', 'cantidad' => 30],
            ['nombre' => 'Yuquitas', 'cantidad' => 3],
            ['nombre' => 'Rend cilantro', 'cantidad' => 1],
        ]);

        // ========================================
        // 15. PICADA MAKABRA
        // ========================================
        $this->crearReceta('PICADA MAKABRA', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub chicharrón toteado', 'cantidad' => 200],
            ['nombre' => 'Solomito de cerdo', 'cantidad' => 1],
            ['nombre' => 'Chorizo de cerdo', 'cantidad' => 1],
            ['nombre' => 'Maiz Dulce', 'cantidad' => 200],
            ['nombre' => 'Tortillas', 'cantidad' => 4],
            ['nombre' => 'Sub pulled pork', 'cantidad' => 60],
            ['nombre' => 'Arepas mini', 'cantidad' => 0.5],
            ['nombre' => 'Empanada paquete', 'cantidad' => 3],
            ['nombre' => 'Sub guacamole', 'cantidad' => 30],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 30],
            ['nombre' => 'Papa capira', 'cantidad' => 120],
            ['nombre' => 'Limon tahiti', 'cantidad' => 50],
            ['nombre' => 'Sub chimichurri', 'cantidad' => 15],
            ['nombre' => 'Sub Bbq de tamarindo y ron', 'cantidad' => 15],
        ]);


                // ========================================
        // 16. TOMAHACK PARRILLERO
        // ========================================
        $this->crearReceta('TOMAHACK PARRILLERO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Tomahawk de cerdo', 'cantidad' => 1],
            ['nombre' => 'Sub chutney de mango', 'cantidad' => 40],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
            ['nombre' => 'Brotes', 'cantidad' => 1],
        ]);

        // ========================================
        // 17. COSTICHI
        // ========================================
        $this->crearReceta('COSTICHI', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub costichi asiatico', 'cantidad' => 1],
            ['nombre' => 'Sub pure de papa', 'cantidad' => 120],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Cebollin criollo', 'cantidad' => 1],
            ['nombre' => 'Sub puerro crocante', 'cantidad' => 20],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
        ]);

        // ========================================
        // 18. COSTILLAS TAMARINDO
        // ========================================
        $this->crearReceta('COSTILLAS TAMARINDO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Costilla San Luis', 'cantidad' => 1],
            ['nombre' => 'Sub Bbq de tamarindo y ron', 'cantidad' => 50],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Cebollin criollo', 'cantidad' => 1],
            ['nombre' => 'Sub pure de papa', 'cantidad' => 120],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
        ]);

        // ========================================
        // 19. PUNTA DE ANCA DE CERDO
        // ========================================
        $this->crearReceta('PUNTA DE ANCA DE CERDO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Punta de anca de cerdo', 'cantidad' => 1],
            ['nombre' => 'Sub chimichurri', 'cantidad' => 20],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 20],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
        ]);

        // ========================================
        // 20. MEDALLONES DE SOLOMITO
        // ========================================
        $this->crearReceta('MEDALLONES DE SOLOMITO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Medallones de Solomito', 'cantidad' => 1],
            ['nombre' => 'Sub chimichurri', 'cantidad' => 20],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 20],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
        ]);

        // ========================================
        // 21. LOMO DE CERDO CREMOSO
        // ========================================
        $this->crearReceta('LOMO DE CERDO CREMOSO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Solomito de cerdo', 'cantidad' => 1],
            ['nombre' => 'Tocineta', 'cantidad' => 1.32],
            ['nombre' => 'Sub pasta de champiñon', 'cantidad' => 40],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 20],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
        ]);

        // ========================================
        // 22. PECHUGA AL GRILL
        // ========================================
        $this->crearReceta('PECHUGA AL GRILL', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Pechuga filete', 'cantidad' => 250],
            ['nombre' => 'Sub salsa de gulupa', 'cantidad' => 40],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 20],
            ['nombre' => 'Brotes', 'cantidad' => 1],
        ]);

        // ========================================
        // 23. ROLLING DE POLLO
        // ========================================
        $this->crearReceta('ROLLING DE POLLO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub pollo relleno', 'cantidad' => 1],
            ['nombre' => 'Queso parmesano', 'cantidad' => 5],
            ['nombre' => 'Sub salsa cuatro quesos', 'cantidad' => 80],
            ['nombre' => 'Arepa de yuca', 'cantidad' => 1],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 1],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Sub mayocilantro', 'cantidad' => 20],
            ['nombre' => 'Palos pinchuzo', 'cantidad' => 1],
            ['nombre' => 'Rend cilantro', 'cantidad' => 1],
        ]);


                // ========================================
        // 24. ENCHOCOLADO DE CHICHARRÓN
        // ========================================
        $this->crearReceta('ENCHOCOLADO DE CHICHARRÓN', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Maiz Dulce', 'cantidad' => 200],
            ['nombre' => 'Mantequilla', 'cantidad' => 20],
            ['nombre' => 'Sub sour makabra', 'cantidad' => 100],
            ['nombre' => 'Tajín', 'cantidad' => 2],
            ['nombre' => 'Sub chicharrón toteado', 'cantidad' => 200],
            ['nombre' => 'Queso parmesano', 'cantidad' => 20],
            ['nombre' => 'Cebollin criollo', 'cantidad' => 2],
            ['nombre' => 'Rend cilantro', 'cantidad' => 1],
        ]);

        // ========================================
        // 25. LOMO PIMIENTA
        // ========================================
        $this->crearReceta('LOMO PIMIENTA', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Medallones de solomito', 'cantidad' => 1],
            ['nombre' => 'Sub salsa pimienta', 'cantidad' => 80],
            ['nombre' => 'Chorizo de cerdo', 'cantidad' => 1],
            ['nombre' => 'Mazorca', 'cantidad' => 100],
            ['nombre' => 'Papa capira', 'cantidad' => 120],
            ['nombre' => 'Queso parmesano', 'cantidad' => 5],
            ['nombre' => 'Mantequilla', 'cantidad' => 10],
            ['nombre' => 'Romero', 'cantidad' => 5],
            ['nombre' => 'Tequila para flamear', 'cantidad' => 15],
        ]);

        // ========================================
        // 26. MADURO MAKABRO
        // ========================================
        $this->crearReceta('MADURO MAKABRO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Plátano Maduro', 'cantidad' => 250],
            ['nombre' => 'Sub pulled pork', 'cantidad' => 90],
            ['nombre' => 'Queso Mozzarella Tajado', 'cantidad' => 2],
            ['nombre' => 'Sub Bbq de tamarindo y ron', 'cantidad' => 40],
            ['nombre' => 'Sub sour makabra', 'cantidad' => 30],
            ['nombre' => 'Sub guacamole', 'cantidad' => 30],
            ['nombre' => 'Sub pico de gallo', 'cantidad' => 30],
            ['nombre' => 'Sub puerro crocante', 'cantidad' => 40],
        ]);

        // ========================================
        // 27. ASADO DE TIRA
        // ========================================
        $this->crearReceta('ASADO DE TIRA', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Sub asado de tira', 'cantidad' => 0.5],
            ['nombre' => 'Sub pure de papa', 'cantidad' => 60],
            ['nombre' => 'Sub pure de plátano', 'cantidad' => 60],
            ['nombre' => 'Queso parmesano', 'cantidad' => 10],
            ['nombre' => 'Sub puerro crocante', 'cantidad' => 1],
            ['nombre' => 'Rend cilantro', 'cantidad' => 1],
        ]);

        // ========================================
        // 28. RITO MAKABRO
        // ========================================
        $this->crearReceta('RITO MAKABRO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Medallones de solomito', 'cantidad' => 1],
            ['nombre' => 'Queso parmesano', 'cantidad' => 5],
            ['nombre' => 'Sub salsa de vino tinto', 'cantidad' => 60],
            ['nombre' => 'Sub arroz blanco', 'cantidad' => 120],
            ['nombre' => 'Sub yuca crocante', 'cantidad' => 30],
            ['nombre' => 'Crema de leche colanta', 'cantidad' => 100],
            ['nombre' => 'Sub pasta de champiñon', 'cantidad' => 50],
            ['nombre' => 'Sub salsa costichi', 'cantidad' => 20],
            ['nombre' => 'Fondo de costilla', 'cantidad' => 100],
            ['nombre' => 'Mezclum', 'cantidad' => 20],
            ['nombre' => 'Sub mango encurtido', 'cantidad' => 10],
            ['nombre' => 'Sub zanahoria encurtida', 'cantidad' => 10],
            ['nombre' => 'Sub nuez garrapiñada', 'cantidad' => 10],
            ['nombre' => 'Sub vinagreta de gulupa', 'cantidad' => 10],
            ['nombre' => 'Sub cherrys glaseados', 'cantidad' => 10],
            ['nombre' => 'Brotes', 'cantidad' => 1],
        ]);


                // ========================================
        // 29. DORITOS TENDERS
        // ========================================
        $this->crearReceta('DORITOS TENDERS', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Pechuga filete', 'cantidad' => 125],
            ['nombre' => 'Sub harina compuesta', 'cantidad' => 30],
            ['nombre' => 'Panko', 'cantidad' => 30],
            ['nombre' => 'Doritos', 'cantidad' => 30],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Queso parmesano', 'cantidad' => 5],
            ['nombre' => 'Sub salsa de panela', 'cantidad' => 20],
            ['nombre' => 'Hojas De Tamal', 'cantidad' => 5],
        ]);

        // ========================================
        // 30. MINI BURGERS X2
        // ========================================
        $this->crearReceta('MINI BURGERS X2', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Pan Mini Burguer', 'cantidad' => 2],
            ['nombre' => 'Sub carne de hamburguesa', 'cantidad' => 50],
            ['nombre' => 'Pechuga filete', 'cantidad' => 0.25],
            ['nombre' => 'Sub salsa hamburguesera', 'cantidad' => 20],
            ['nombre' => 'Queso philadelphia', 'cantidad' => 40],
            ['nombre' => 'Sub salsa de panela', 'cantidad' => 120],
            ['nombre' => 'Lechuga cogollo europeo', 'cantidad' => 20],
            ['nombre' => 'Papa Francesa', 'cantidad' => 120],
            ['nombre' => 'Salsa de tomate', 'cantidad' => 120],
        ]);

                // ========================================
        // 31. REINA MILHOJA
        // ========================================
        $this->crearReceta('REINA MILHOJA', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Masa para hojaldre', 'cantidad' => 80],
            ['nombre' => 'Arequipe', 'cantidad' => 20],
            ['nombre' => 'Crema pastelera', 'cantidad' => 10],
            ['nombre' => 'Fresa', 'cantidad' => 10],
            ['nombre' => 'Hierbabuena', 'cantidad' => 5],
            ['nombre' => 'Salsa de chocolate', 'cantidad' => 10],
        ]);

        // ========================================
        // 32. SUSPIRO LIMEÑO
        // ========================================
        $this->crearReceta('SUSPIRO LIMEÑO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Arequipe', 'cantidad' => 39.15],
            ['nombre' => 'Canela molida', 'cantidad' => 0.39],
            ['nombre' => 'Crema de leche de vida', 'cantidad' => 58.73],
            ['nombre' => 'Fresa', 'cantidad' => 0.39],
            ['nombre' => 'Huevos', 'cantidad' => 1],
        ]);

        // ========================================
        // 33. CHEESECAKE OREO
        // ========================================
        $this->crearReceta('CHEESECAKE OREO', $categoriaRecetas->id, $unidadCompraId, [
            ['nombre' => 'Crema de leche de vida', 'cantidad' => 400],
            ['nombre' => 'Cobertura de chocolate', 'cantidad' => 200],
            ['nombre' => 'Crema de leche de vida', 'cantidad' => 200],
            ['nombre' => 'Leche condensada', 'cantidad' => 400],
            ['nombre' => 'Queso crema', 'cantidad' => 500],
            ['nombre' => 'Vainilla', 'cantidad' => 10],
            ['nombre' => 'Gelatina sin sabor', 'cantidad' => 18],
            ['nombre' => 'Agua', 'cantidad' => 60],
            ['nombre' => 'Galleta oreo', 'cantidad' => 108],
            ['nombre' => 'Mantequilla', 'cantidad' => 100],
        ]);

        $this->command->info('✅ Todas las recetas fueron creadas exitosamente!');
    }

    /**
     * Crear una receta (producto tipo 'venta') con sus ingredientes
     */
    private function crearReceta(string $nombre, int $categoriaId, int $unidadCompraId, array $ingredientes): void
    {
        $sedeId = DB::table('sedes')->orderBy('id', 'asc')->value('id') ?? 1;

        $receta = Producto::firstOrCreate(
            ['nombre' => $nombre, 'sede_id' => $sedeId],
            [
                'nombre' => $nombre,
                'sede_id' => $sedeId,
                'tipo' => 'venta',
                'categoria_id' => $categoriaId,
                'unidad_compra_id' => $unidadCompraId,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ✅ Eliminar ingredientes antiguos (evita duplicados)
        DB::table('recetas_bom')
            ->where('producto_padre_id', $receta->id)
            ->delete();

        $contador = 0;
        foreach ($ingredientes as $ingData) {
            $nombreBuscado = trim($ingData['nombre']);
            
            // 1. Buscar en la sede actual
            $ingrediente = Producto::withoutGlobalScope('sede')
                ->where('sede_id', $sedeId)
                ->whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower($nombreBuscado)])
                ->first();

            // 2. Si no se encuentra, buscar globalmente
            if (!$ingrediente) {
                $ingrediente = Producto::withoutGlobalScope('sede')
                    ->whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower($nombreBuscado)])
                    ->first();
            }
            
            if ($ingrediente) {
                // ✅ Verificar si ya existe
                $existe = DB::table('recetas_bom')
                    ->where('producto_padre_id', $receta->id)
                    ->where('producto_hijo_id', $ingrediente->id)
                    ->exists();
                
                if (!$existe) {
                    DB::table('recetas_bom')->insert([
                        'producto_padre_id' => $receta->id,
                        'producto_hijo_id' => $ingrediente->id,
                        'cantidad' => $ingData['cantidad'],
                        'nota' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $contador++;
                }
            } else {
                $this->command->warn("⚠️ Ingrediente no encontrado: '{$ingData['nombre']}' para '{$nombre}'");
            }
        }

        $this->command->info("✅ Receta '{$nombre}' creada con {$contador} ingredientes");
    }
}