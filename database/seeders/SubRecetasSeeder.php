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
            ['nombre' => 'Aderezo Bbq', 'cantidad' => 50],
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
        // SUB MAYONESA CHIMICHURRÍ (NUEVA)
        // ========================================
        $this->crearSubreceta('SUB MAYONESA CHIMICHURRÍ', $categoriaSubrecetas->id, [
            ['nombre' => 'Mayonesa', 'cantidad' => 500],
            ['nombre' => 'Sub chimichurri', 'cantidad' => 100],
            ['nombre' => 'Mostaza', 'cantidad' => 30],
            ['nombre' => 'Azucar blanca', 'cantidad' => 20],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
        ]);

        // ========================================
        // SUB MAYO SWEET CHILLI (NUEVA)
        // ========================================
        $this->crearSubreceta('SUB MAYO SWEET CHILLI', $categoriaSubrecetas->id, [
            ['nombre' => 'Sweet Chilly Sauce', 'cantidad' => 150],
            ['nombre' => 'Mayonesa', 'cantidad' => 500],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 20],
            ['nombre' => 'Sal común', 'cantidad' => 2],
            ['nombre' => 'Pimienta negra', 'cantidad' => 0.5],
        ]);
        // ========================================
        // 5. SUB Bbq DE TAMARINDO Y RON
        // ========================================
        $this->crearSubreceta('SUB Bbq DE TAMARINDO Y RON', $categoriaSubrecetas->id, [
            ['nombre' => 'Aderezo Bbq', 'cantidad' => 1000],
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
            ['nombre' => 'Rend zumo de Maracuya', 'cantidad' => 200],
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


                // ========================================
        // 11. SUB PURE DE PAPA
        // ========================================
        $this->crearSubreceta('SUB PURE DE PAPA', $categoriaSubrecetas->id, [
            ['nombre' => 'Papa capira', 'cantidad' => 1000],
            ['nombre' => 'Crema de leche de vida', 'cantidad' => 200],
            ['nombre' => 'Romero', 'cantidad' => 5],
            ['nombre' => 'Mantequilla', 'cantidad' => 50],
            ['nombre' => 'Agua', 'cantidad' => 2700],
            ['nombre' => 'Sal común', 'cantidad' => 25],
        ]);

        // ========================================
        // 12. SUB CARNE AL PASTOR
        // ========================================
        $this->crearSubreceta('SUB CARNE AL PASTOR', $categoriaSubrecetas->id, [
            ['nombre' => 'Pierna de Cerdo', 'cantidad' => 10000],
            ['nombre' => 'Clavo de olor', 'cantidad' => 2],
            ['nombre' => 'Comino', 'cantidad' => 20],
            ['nombre' => 'Achiote molido', 'cantidad' => 150],
            ['nombre' => 'Oregano escamas', 'cantidad' => 20],
            ['nombre' => 'Ajo Pelado', 'cantidad' => 60],
            ['nombre' => 'Rend zumo de Naranja', 'cantidad' => 1000],
            ['nombre' => 'Vinagre de manzana', 'cantidad' => 500],
            ['nombre' => 'Sal común', 'cantidad' => 150],
            ['nombre' => 'Chile guajillo', 'cantidad' => 20],
            ['nombre' => 'Pimienta negra', 'cantidad' => 10],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 200],
        ]);

        // ========================================
        // 13. SUB SALSA TATEMADA
        // ========================================
        $this->crearSubreceta('SUB SALSA TATEMADA', $categoriaSubrecetas->id, [
            ['nombre' => 'Cilantro', 'cantidad' => 30],
            ['nombre' => 'Sal común', 'cantidad' => 20],
            ['nombre' => 'Pimienta negra', 'cantidad' => 5],
            ['nombre' => 'Pimenton rojo', 'cantidad' => 1000],
            ['nombre' => 'Tomate chonto', 'cantidad' => 1600],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 400],
            ['nombre' => 'Ajo pelado', 'cantidad' => 50],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 250],
            ['nombre' => 'Azucar blanca', 'cantidad' => 20],
        ]);

        // ========================================
        // 14. SUB GUACAMOLE
        // ========================================
       // ========================================
        // SUB GUACAMOLE (ACTUALIZADO)
        // ========================================
        $this->crearSubreceta('SUB GUACAMOLE', $categoriaSubrecetas->id, [
            ['nombre' => 'Rend aguacate hass', 'cantidad' => 300],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 20],
            ['nombre' => 'Rend cilantro', 'cantidad' => 30],
            ['nombre' => 'Tomate chonto', 'cantidad' => 20],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 50],
            ['nombre' => 'Rend mango tommy', 'cantidad' => 20],
            ['nombre' => 'Sal común', 'cantidad' => 2],
            ['nombre' => 'Pimienta negra', 'cantidad' => 1],
        ]);

        // ========================================
        // 15. SUB PULLED PORK
        // ========================================
        $this->crearSubreceta('SUB PULLED PORK', $categoriaSubrecetas->id, [
            ['nombre' => 'Bondiola De Cerdo', 'cantidad' => 1500],
            ['nombre' => 'Aceite Bidón', 'cantidad' => 50],
            ['nombre' => 'Ajo Pelado', 'cantidad' => 80],
            ['nombre' => 'Ajo en polvo', 'cantidad' => 8],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 190],
            ['nombre' => 'Cebolla rama', 'cantidad' => 120],
            ['nombre' => 'Cebolla roja', 'cantidad' => 190],
            ['nombre' => 'Laurel en hojas', 'cantidad' => 2],
            ['nombre' => 'Miel', 'cantidad' => 50],
            ['nombre' => 'Oregano escamas', 'cantidad' => 2],
            ['nombre' => 'Paprika', 'cantidad' => 3],
            ['nombre' => 'Pimenton rojo', 'cantidad' => 180],
            ['nombre' => 'Pimienta negra', 'cantidad' => 3],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Aderezo Bbq', 'cantidad' => 200],
            ['nombre' => 'Segu olle tinto', 'cantidad' => 40],
        ]);

        // ========================================
        // 16. SUB SOUR MAKABRA
        // ========================================
        $this->crearSubreceta('SUB SOUR MAKABRA', $categoriaSubrecetas->id, [
            ['nombre' => 'Crema agria', 'cantidad' => 500],
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 120],
            ['nombre' => 'Ajo pelado', 'cantidad' => 20],
            ['nombre' => 'Pimienta cayena', 'cantidad' => 5],
            ['nombre' => 'Queso parmesano', 'cantidad' => 20],
            ['nombre' => 'Sal común', 'cantidad' => 2],
            ['nombre' => 'Sazon completo badia', 'cantidad' => 2],
            ['nombre' => 'Sweet Chilly Sauce', 'cantidad' => 20],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 30],
        ]);

        // ========================================
        // 17. SUB COSTICHI ASIATICO
        // ========================================
        $this->crearSubreceta('SUB COSTICHI ASIATICO', $categoriaSubrecetas->id, [
            ['nombre' => 'Salsa de soya', 'cantidad' => 2000],
            ['nombre' => 'Azucar blanca', 'cantidad' => 1000],
            ['nombre' => 'Jengibre', 'cantidad' => 20],
            ['nombre' => 'Coriandro', 'cantidad' => 10],
            ['nombre' => 'Costichi', 'cantidad' => 10],
            ['nombre' => 'Agua', 'cantidad' => 2000],
        ]);

        // ========================================
        // 18. SUB CEBOLLA ENCURTIDA
        // ========================================
        $this->crearSubreceta('SUB CEBOLLA ENCURTIDA', $categoriaSubrecetas->id, [
            ['nombre' => 'Cebolla roja', 'cantidad' => 500],
            ['nombre' => 'Agua', 'cantidad' => 300],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 300],
            ['nombre' => 'Coriandro', 'cantidad' => 3],
            ['nombre' => 'Romero', 'cantidad' => 10],
            ['nombre' => 'Azucar blanca', 'cantidad' => 50],
            ['nombre' => 'Sal común', 'cantidad' => 50],
            ['nombre' => 'Pimienta negra', 'cantidad' => 1],
        ]);

        // ========================================
        // 19. SUB ZANAHORIA ENCURTIDA
        // ========================================
         $this->crearSubreceta('SUB ZANAHORIA ENCURTIDA', $categoriaSubrecetas->id, [
            ['nombre' => 'Zanahoria', 'cantidad' => 200],
            ['nombre' => 'Agua', 'cantidad' => 200],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 30],
            ['nombre' => 'Coriandro', 'cantidad' => 30],
            ['nombre' => 'Romero', 'cantidad' => 200],
            ['nombre' => 'Azucar blanca', 'cantidad' => 150],
            ['nombre' => 'Sal común', 'cantidad' => 151],
            ['nombre' => 'Pimienta negra', 'cantidad' => 152],
        ]);

        // ========================================
        // 20. SUB MANGO ENCURTIDO
        // ========================================
        $this->crearSubreceta('SUB MANGO ENCURTIDO', $categoriaSubrecetas->id, [
            ['nombre' => 'Mango tommy', 'cantidad' => 500],
            ['nombre' => 'Agua', 'cantidad' => 300],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 300],
            ['nombre' => 'Coriandro', 'cantidad' => 3],
            ['nombre' => 'Romero', 'cantidad' => 10],
            ['nombre' => 'Azucar blanca', 'cantidad' => 50],
            ['nombre' => 'Sal común', 'cantidad' => 50],
            ['nombre' => 'Pimienta negra', 'cantidad' => 1],
        ]);

        // ========================================
        // SUB VINAGRETA DE GULUPA (NUEVA)
        // ========================================
        $this->crearSubreceta('SUB VINAGRETA DE GULUPA', $categoriaSubrecetas->id, [
            ['nombre' => 'Sub salsa de gulupa', 'cantidad' => 200],
            ['nombre' => 'Aceite bidón', 'cantidad' => 300],
            ['nombre' => 'Mostaza', 'cantidad' => 30],
            ['nombre' => 'Limon tahiti', 'cantidad' => 60],
            ['nombre' => 'Sal común', 'cantidad' => 3],
            ['nombre' => 'Pimienta negra', 'cantidad' => 1],
        ]);

        
        // ========================================
        // 21. SUB MERMELADA DE TOCINETA
        // ========================================
        $this->crearSubreceta('SUB MERMELADA DE TOCINETA', $categoriaSubrecetas->id, [
            ['nombre' => 'Tocineta', 'cantidad' => 34],
            ['nombre' => 'Azucar morena', 'cantidad' => 200],
            ['nombre' => 'Miel', 'cantidad' => 5],
            ['nombre' => 'Agua', 'cantidad' => 50],
        ]);

        // ========================================
        // 22. SUB SALSA DE GULUPA
        // ========================================
        $this->crearSubreceta('SUB SALSA DE GULUPA', $categoriaSubrecetas->id, [
            ['nombre' => 'Gulupa', 'cantidad' => 300],
            ['nombre' => 'Panela', 'cantidad' => 120],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Ajo pelado', 'cantidad' => 5],
            ['nombre' => 'Pimienta negra', 'cantidad' => 2],
            ['nombre' => 'Jalapeño verde', 'cantidad' => 10],
            ['nombre' => 'Aji Dulce', 'cantidad' => 15],
        ]);

        // ========================================
        // 23. SUB SALSA CUATRO QUESOS
        // ========================================
        $this->crearSubreceta('SUB SALSA CUATRO QUESOS', $categoriaSubrecetas->id, [
            ['nombre' => 'Crema de leche de vida', 'cantidad' => 250],
            ['nombre' => 'Crema agria', 'cantidad' => 250],
            ['nombre' => 'Leche Entera', 'cantidad' => 100],
            ['nombre' => 'Queso mozzarella tajado', 'cantidad' => 8],
            ['nombre' => 'Queso cheddar tajado', 'cantidad' => 5],
            ['nombre' => 'Queso parmesano', 'cantidad' => 100],
            ['nombre' => 'Mostaza', 'cantidad' => 100],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Pimienta negra', 'cantidad' => 10],
        ]);

        // ========================================
        // 24. SUB NUEZ GARRAPIÑADA
        // ========================================
        $this->crearSubreceta('SUB NUEZ GARRAPIÑADA', $categoriaSubrecetas->id, [
            ['nombre' => 'Nuez del brasil', 'cantidad' => 250],
            ['nombre' => 'Mani simple', 'cantidad' => 250],
            ['nombre' => 'Azucar blanca', 'cantidad' => 250],
            ['nombre' => 'Agua', 'cantidad' => 100],
            ['nombre' => 'Esencia de vainilla', 'cantidad' => 1],
        ]);

        // ========================================
        // 25. SUB SALSA DE PANELA
        // ========================================
        $this->crearSubreceta('SUB SALSA DE PANELA', $categoriaSubrecetas->id, [
            ['nombre' => 'Panela', 'cantidad' => 1000],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 200],
            ['nombre' => 'Agua', 'cantidad' => 1500],
            ['nombre' => 'Antioqueño rojo botella', 'cantidad' => 100],
            ['nombre' => 'Ralladura de Naranja', 'cantidad' => 5],
            ['nombre' => 'Pasta de tomate', 'cantidad' => 200],
        ]);

        // ========================================
        // 26. SUB PICO DE GALLO
        // ========================================
        $this->crearSubreceta('SUB PICO DE GALLO', $categoriaSubrecetas->id, [
            ['nombre' => 'Tomate chonto', 'cantidad' => 1000],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 500],
            ['nombre' => 'Cilantro', 'cantidad' => 80],
            ['nombre' => 'Limon tahiti', 'cantidad' => 40],
            ['nombre' => 'Sal común', 'cantidad' => 30],
            ['nombre' => 'Pimienta negra', 'cantidad' => 5],
        ]);

        // ========================================
        // 27. SUB HOGAO
        // ========================================
        $this->crearSubreceta('SUB HOGAO', $categoriaSubrecetas->id, [
            ['nombre' => 'Tomate chonto', 'cantidad' => 1000],
            ['nombre' => 'Ajo pelado', 'cantidad' => 30],
            ['nombre' => 'Cebolla rama', 'cantidad' => 250],
            ['nombre' => 'Comino', 'cantidad' => 2],
            ['nombre' => 'Mantequilla', 'cantidad' => 50],
            ['nombre' => 'Pasta de tomate', 'cantidad' => 20],
            ['nombre' => 'Pimienta negra', 'cantidad' => 2],
            ['nombre' => 'Sal común', 'cantidad' => 15],
            ['nombre' => 'Tomillo molido', 'cantidad' => 2],
        ]);

        // ========================================
        // 28. SUB CHICHARRÓN TOTEADO
        // ========================================
        $this->crearSubreceta('SUB CHICHARRÓN TOTEADO', $categoriaSubrecetas->id, [
            ['nombre' => 'Chicharron - tocino', 'cantidad' => 5000],
            ['nombre' => 'Sal común', 'cantidad' => 300],
            ['nombre' => 'Pimienta negra', 'cantidad' => 300],
            ['nombre' => 'Paprika', 'cantidad' => 3],
            ['nombre' => 'Ajo en polvo', 'cantidad' => 10],
        ]);

        // ========================================
        // 29. SUB CHIMICHURRI
        // ========================================
        $this->crearSubreceta('SUB CHIMICHURRI', $categoriaSubrecetas->id, [
            ['nombre' => 'Perejil crespo', 'cantidad' => 270],
            ['nombre' => 'Aceite', 'cantidad' => 1500],
            ['nombre' => 'Aji Dulce', 'cantidad' => 140],
            ['nombre' => 'Ajo pelado', 'cantidad' => 60],
            ['nombre' => 'Oregano escamas', 'cantidad' => 5],
            ['nombre' => 'Pimienta negra', 'cantidad' => 5],
            ['nombre' => 'Piña oro miel', 'cantidad' => 110],
            ['nombre' => 'Sal común', 'cantidad' => 15],
            ['nombre' => 'Sazon completo badia', 'cantidad' => 5],
            ['nombre' => 'Sweet Chilly Sauce', 'cantidad' => 20],
            ['nombre' => 'Vinagre blanco', 'cantidad' => 35],
        ]);

        // ========================================
        // 30. SUB CEBOLLA CRISPY
        // ========================================
        $this->crearSubreceta('SUB CEBOLLA CRISPY', $categoriaSubrecetas->id, [
            ['nombre' => 'Cebolla blanca', 'cantidad' => 1000],
            ['nombre' => 'Harina de trigo', 'cantidad' => 60],
        ]);

                // ========================================
        // 31. SUB CHERRYS GLASEADOS
        // ========================================
        $this->crearSubreceta('SUB CHERRYS GLASEADOS', $categoriaSubrecetas->id, [
            ['nombre' => 'Tomate cherry', 'cantidad' => 230],
            ['nombre' => 'Azucar blanca', 'cantidad' => 40],
            ['nombre' => 'Oregano escamas', 'cantidad' => 3],
            ['nombre' => 'Sal común', 'cantidad' => 2],
            ['nombre' => 'Salsa teriyaki', 'cantidad' => 20],
            ['nombre' => 'Sweet Chilly Sauce', 'cantidad' => 20],
            ['nombre' => 'Segu olle tinto', 'cantidad' => 20],
        ]);

        // ========================================
        // 32. SUB SALSA DE PIMIENTA
        // ========================================
        $this->crearSubreceta('SUB SALSA DE PIMIENTA', $categoriaSubrecetas->id, [
            ['nombre' => 'Cebolla blanca', 'cantidad' => 300],
            ['nombre' => 'Ajo pelado', 'cantidad' => 10],
            ['nombre' => 'Caldo de res', 'cantidad' => 150],
            ['nombre' => 'Chorizo de cerdo', 'cantidad' => 0.5],
            ['nombre' => 'Crema de leche colanta', 'cantidad' => 350],
            ['nombre' => 'Mostaza', 'cantidad' => 40],
            ['nombre' => 'Pimienta negra', 'cantidad' => 10],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Salsa inglesa', 'cantidad' => 60],
        ]);

        // ========================================
        // 33. SUB ARROZ COCO
        // ========================================
        $this->crearSubreceta('SUB ARROZ COCO', $categoriaSubrecetas->id, [
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Agua', 'cantidad' => 2000],
            ['nombre' => 'Aceite', 'cantidad' => 30],
            ['nombre' => 'Arroz', 'cantidad' => 1000],
        ]);

        // ========================================
        // 34. SUB PASTA DE CHAMPIÑONES
        // ========================================
        $this->crearSubreceta('SUB PASTA DE CHAMPIÑONES', $categoriaSubrecetas->id, [
            ['nombre' => 'Cebolla blanca', 'cantidad' => 300],
            ['nombre' => 'Ajinomoto', 'cantidad' => 2],
            ['nombre' => 'Champiñon entero', 'cantidad' => 1000],
            ['nombre' => 'Crema de leche de vida', 'cantidad' => 2000],
            ['nombre' => 'Mantequilla', 'cantidad' => 20],
            ['nombre' => 'Pimienta negra', 'cantidad' => 2],
            ['nombre' => 'Queso parmesano', 'cantidad' => 300],
            ['nombre' => 'Sal común', 'cantidad' => 2],
        ]);

        // ========================================
        // 35. SUB COPETE DESMECHADO
        // ========================================
        $this->crearSubreceta('SUB COPETE DESMECHADO', $categoriaSubrecetas->id, [
            ['nombre' => 'Desmechada copete', 'cantidad' => 1000],
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 100],
            ['nombre' => 'Pimenton rojo', 'cantidad' => 100],
            ['nombre' => 'Ajo pelado', 'cantidad' => 20],
            ['nombre' => 'Aderezo Bbq', 'cantidad' => 100],
        ]);

        // ========================================
        // 36. SUB ASADO DE TIRA
        // ========================================
        $this->crearSubreceta('SUB ASADO DE TIRA', $categoriaSubrecetas->id, [
            ['nombre' => 'Asado de tira', 'cantidad' => 5],
            ['nombre' => 'Segu olle tinto', 'cantidad' => 500],
            ['nombre' => 'Zanahoria', 'cantidad' => 200],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 200],
            ['nombre' => 'Apio', 'cantidad' => 100],
            ['nombre' => 'Ajo pelado', 'cantidad' => 50],
            ['nombre' => 'Panela', 'cantidad' => 200],
            ['nombre' => 'Pasta de tomate', 'cantidad' => 30],
        ]);

        // ========================================
        // 37. SUB CROQUETAS PULLED PORK
        // ========================================
        $this->crearSubreceta('SUB CROQUETAS PULLED PORK', $categoriaSubrecetas->id, [
            ['nombre' => 'Pulled Pork', 'cantidad' => 400],
            ['nombre' => 'Queso cheddar tajado', 'cantidad' => 10],
            ['nombre' => 'Harina de trigo', 'cantidad' => 30],
            ['nombre' => 'Mantequilla', 'cantidad' => 30],
            ['nombre' => 'Fondo', 'cantidad' => 400],
        ]);

        // ========================================
        // 38. SUB PUERRO CROCANTE
        // ========================================
        $this->crearSubreceta('SUB PUERRO CROCANTE', $categoriaSubrecetas->id, [
            ['nombre' => 'Cebolla puerro', 'cantidad' => 800],
            ['nombre' => 'Azucar blanca', 'cantidad' => 250],
        ]);

        // ========================================
        // 39. SUB CHEESECAKE DE OREO
        // ========================================
        $this->crearSubreceta('SUB CHEESECAKE DE OREO', $categoriaSubrecetas->id, [
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

        // ========================================
        // 40. SUB ALAS DE POLLO SALMUERA
        // ========================================
        $this->crearSubreceta('SUB ALAS DE POLLO SALMUERA', $categoriaSubrecetas->id, [
            ['nombre' => 'Alas de pollo', 'cantidad' => 1000],
            ['nombre' => 'Sal común', 'cantidad' => 50],
            ['nombre' => 'Agua', 'cantidad' => 1000],
            ['nombre' => 'Azucar blanca', 'cantidad' => 25],
        ]);

        // ========================================
        // 41. SUB SALSA COSTICHI
        // ========================================
        $this->crearSubreceta('SUB SALSA COSTICHI', $categoriaSubrecetas->id, [
            ['nombre' => 'Salsa de soya', 'cantidad' => 2000],
            ['nombre' => 'Azucar blanca', 'cantidad' => 1000],
            ['nombre' => 'Jengibre', 'cantidad' => 20],
            ['nombre' => 'Ajo pelado', 'cantidad' => 20],
            ['nombre' => 'Agua', 'cantidad' => 2000],
            ['nombre' => 'Coriandro', 'cantidad' => 10],
        ]);

        // ========================================
        // 42. SUB PASTA CHAMPIÑONES (REPETIDA? - la omites)
        // Nota: La 34 ya es SUB PASTA DE CHAMPIÑONES, esta es similar
        // ========================================
        // SUB SALSA DE VINO TINTO
        // ========================================
        $this->crearSubreceta('SUB SALSA DE VINO TINTO', $categoriaSubrecetas->id, [
            ['nombre' => 'Segu olle tinto', 'cantidad' => 1000],
            ['nombre' => 'Fondo de res', 'cantidad' => 300],
            ['nombre' => 'Azucar blanca', 'cantidad' => 100],
            ['nombre' => 'Cebolla blanca', 'cantidad' => 150],
            ['nombre' => 'Ajo Pelado', 'cantidad' => 20],
            ['nombre' => 'Fecula de maiz', 'cantidad' => 10],
            ['nombre' => 'Pimienta negra', 'cantidad' => 0.5],
            ['nombre' => 'Sal común', 'cantidad' => 10],
        ]);

        // ========================================
        // 43. SUB ARROZ COCIDO
        // ========================================
        $this->crearSubreceta('SUB ARROZ COCIDO', $categoriaSubrecetas->id, [
            ['nombre' => 'Sal común', 'cantidad' => 10],
            ['nombre' => 'Agua', 'cantidad' => 2000],
            ['nombre' => 'Aceite Bidón', 'cantidad' => 30],
            ['nombre' => 'Arroz', 'cantidad' => 1000],
        ]);

        // ========================================
        // 44. SUB ESPUMA DE LIMÓN
        // ========================================
        $this->crearSubreceta('SUB ESPUMA DE LIMÓN', $categoriaSubrecetas->id, [
            ['nombre' => 'Rend zumo de limón', 'cantidad' => 180],
            ['nombre' => 'Sirope simple', 'cantidad' => 180],
            ['nombre' => 'Clara de huevo', 'cantidad' => 180],
            ['nombre' => 'Capsula de c02', 'cantidad' => 1],
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