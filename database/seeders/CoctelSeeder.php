<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Catalog\Producto;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\UnidadMedida;

class CoctelSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🍹 Cargando Cocteles...');

        // Obtener o crear la categoría de bebidas
        $categoriaBebidas = Categoria::firstOrCreate(
            ['nombre' => 'Sub recetas - bebidas'],
            [
                'nombre' => 'Sub recetas - bebidas',
                'slug' => \Illuminate\Support\Str::slug('Sub recetas - bebidas'),
                'descripcion' => 'Recetas para cocteles y bebidas',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Unidad por defecto para cocteles (generalmente 'und' o 'ml')
        $unidad = UnidadMedida::where('abreviatura', 'und')->first();
        $unidadCompraId = $unidad ? $unidad->id : 4;

        $this->command->info("📂 Categoría Bebidas ID: {$categoriaBebidas->id}");

        // ========================================
        // Lista de cocteles con sus ingredientes
        // ========================================
        $cocteles = [
            // ========================================
            // 1. SERENO
            // ========================================
            [
                'nombre' => 'SERENO',
                'ingredientes' => [
                    ['nombre' => 'Pisco quebranta la botija', 'cantidad' => 30],
                    ['nombre' => "Jack Daniel's Apple", 'cantidad' => 30],
                    ['nombre' => 'Sirope de kiwi', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Angostura Special', 'cantidad' => 1],
                    ['nombre' => 'Sub espuma de limón', 'cantidad' => 30],
                    ['nombre' => 'Manzana verde', 'cantidad' => 11],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 2. ORIGEN
            // ========================================
            [
                'nombre' => 'ORIGEN',
                'ingredientes' => [
                    ['nombre' => 'Johnnie walker black label', 'cantidad' => 60],
                    ['nombre' => 'Licor de café', 'cantidad' => 15],
                    ['nombre' => 'Miel de agave', 'cantidad' => 7.5],
                    ['nombre' => 'Angostura Cacao', 'cantidad' => 1.2],
                    ['nombre' => 'Piel naranja', 'cantidad' => 2],
                    ['nombre' => 'Granos de café', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 3. VIDA ETERNA
            // ========================================
            [
                'nombre' => 'VIDA ETERNA',
                'ingredientes' => [
                    ['nombre' => 'Gin dry shipper', 'cantidad' => 30],
                    ['nombre' => 'Limoncello Villa Massa', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Antioqueño rojo botella', 'cantidad' => 7.5],
                    ['nombre' => 'Sirope de kiwi', 'cantidad' => 30],
                    ['nombre' => 'Angostura Orange', 'cantidad' => 1],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 1],
                    ['nombre' => 'Limon tahiti', 'cantidad' => 5],
                    ['nombre' => 'Vela volcan', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 4. NUBE DE COCO
            // ========================================
            [
                'nombre' => 'NUBE DE COCO',
                'ingredientes' => [
                    ['nombre' => 'Tequila Bestia', 'cantidad' => 60],
                    ['nombre' => 'Crema De Coco Real', 'cantidad' => 30],
                    ['nombre' => 'Ron De Coco Malibú', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Angostura Aromatic', 'cantidad' => 1],
                    ['nombre' => 'Tajín', 'cantidad' => 1],
                    ['nombre' => 'Piña oro miel', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 5. SANGRE VERDE
            // ========================================
            [
                'nombre' => 'SANGRE VERDE',
                'ingredientes' => [
                    ['nombre' => 'Gin dry shipper', 'cantidad' => 45],
                    ['nombre' => 'Licor de melón brisar', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 15],
                    ['nombre' => 'Sirope de kiwi', 'cantidad' => 15],
                    ['nombre' => 'Tónica 1976 Indi', 'cantidad' => 15],
                    ['nombre' => 'Sub espuma de limón', 'cantidad' => 30],
                    ['nombre' => 'Rama gipso', 'cantidad' => 10],
                    ['nombre' => 'Fibra de coco', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 6. ROSA SALVAJE
            // ========================================
            [
                'nombre' => 'ROSA SALVAJE',
                'ingredientes' => [
                    ['nombre' => 'Tequila bestia', 'cantidad' => 45],
                    ['nombre' => 'Mezcal 400 conejos', 'cantidad' => 15],
                    ['nombre' => 'Syrup De Flor De Jamaica', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Hielo seco', 'cantidad' => 20],
                    ['nombre' => 'Fibra de coco', 'cantidad' => 5],
                    ['nombre' => 'Polvo de oro', 'cantidad' => 0.5],
                    ['nombre' => 'Rama gipso', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 7. LUNATICO
            // ========================================
            [
                'nombre' => 'LUNATICO',
                'ingredientes' => [
                    ['nombre' => 'Ron medellín dorado', 'cantidad' => 30],
                    ['nombre' => 'Jägermeister Botella', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 60],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 15],
                    ['nombre' => 'Tónica 1976 Indi', 'cantidad' => 30],
                    ['nombre' => 'Rama gipso', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 8. LUZ DE FUEGO
            // ========================================
            [
                'nombre' => 'LUZ DE FUEGO',
                'ingredientes' => [
                    ['nombre' => 'Gin dry shipper', 'cantidad' => 60],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 45],
                    ['nombre' => 'Sirope simple', 'cantidad' => 22.5],
                    ['nombre' => 'Syrup De Uva Isabella', 'cantidad' => 30],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 1],
                    ['nombre' => 'Naranja', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 9. MAKA MAI TAI
            // ========================================
            [
                'nombre' => 'MAKA MAI TAI',
                'ingredientes' => [
                    ['nombre' => 'Ron cortez oro', 'cantidad' => 60],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 30],
                    ['nombre' => 'Syrup de orgeat fines call', 'cantidad' => 30],
                    ['nombre' => 'Angostura Aromatic', 'cantidad' => 1],
                    ['nombre' => 'Piña oro miel', 'cantidad' => 5],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 10. PIEL DE HUMO
            // ========================================
            [
                'nombre' => 'PIEL DE HUMO',
                'ingredientes' => [
                    ['nombre' => 'Mezcal 400 conejos', 'cantidad' => 30],
                    ['nombre' => 'Rend piña oro miel', 'cantidad' => 60],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 22.5],
                    ['nombre' => 'Syrup De Uchuva', 'cantidad' => 15],
                    ['nombre' => 'Sirope simple', 'cantidad' => 22.5],
                    ['nombre' => 'Tajín', 'cantidad' => 1],
                    ['nombre' => 'Rama gipso', 'cantidad' => 5],
                    ['nombre' => 'Romero', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 11. PALOMA
            // ========================================
            [
                'nombre' => 'PALOMA',
                'ingredientes' => [
                    ['nombre' => 'Tequila Bestia', 'cantidad' => 60],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Sirope simple', 'cantidad' => 30],
                    ['nombre' => 'Mezclador De Toronja', 'cantidad' => 100],
                    ['nombre' => 'Romero', 'cantidad' => 2],
                    ['nombre' => 'Toronja', 'cantidad' => 2],
                    ['nombre' => 'Tajin', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 12. MOSCOW MULE
            // ========================================
            [
                'nombre' => 'MOSCOW MULE',
                'ingredientes' => [
                    ['nombre' => 'Vodka orlikoff', 'cantidad' => 60],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Jengibre', 'cantidad' => 30],
                    ['nombre' => 'Agua Tónica Ginger Beer', 'cantidad' => 100],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 2],
                    ['nombre' => 'Limon tahiti', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 13. MARGARITA
            // ========================================
            [
                'nombre' => 'MARGARITA',
                'ingredientes' => [
                    ['nombre' => 'Tequila Bestia', 'cantidad' => 60],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 30],
                    ['nombre' => 'Sirope simple', 'cantidad' => 15],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Sal Común', 'cantidad' => 5],
                    ['nombre' => 'Limon tahiti', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 14. NEGRONI
            // ========================================
            [
                'nombre' => 'NEGRONI',
                'ingredientes' => [
                    ['nombre' => 'Gin dry shipper', 'cantidad' => 30],
                    ['nombre' => 'Campari', 'cantidad' => 30],
                    ['nombre' => 'Martini Vermouth', 'cantidad' => 30],
                ]
            ],
            // ========================================
            // 15. MOJITO
            // ========================================
            [
                'nombre' => 'MOJITO',
                'ingredientes' => [
                    ['nombre' => 'Ron cortez blanco', 'cantidad' => 90],
                    ['nombre' => 'Limon Tahiti', 'cantidad' => 30],
                    ['nombre' => 'Azucar blanca', 'cantidad' => 20],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 5],
                    ['nombre' => 'Soda 250 ml', 'cantidad' => 0.24],
                ]
            ],
            // ========================================
            // 16. COSMOPOLITAN
            // ========================================
            [
                'nombre' => 'COSMOPOLITAN',
                'ingredientes' => [
                    ['nombre' => 'Vodka orlikoff', 'cantidad' => 60],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 15],
                    ['nombre' => 'Zumo De Cranberry', 'cantidad' => 30],
                    ['nombre' => 'Sirope simple', 'cantidad' => 15],
                    ['nombre' => 'Toronja', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 17. OLD FASHION
            // ========================================
            [
                'nombre' => 'OLD FASHION',
                'ingredientes' => [
                    ['nombre' => "Jack Daniel's Old No. 7", 'cantidad' => 60],
                    ['nombre' => 'Angostura Aromatic', 'cantidad' => 2.5],
                    ['nombre' => 'Azucar blanca', 'cantidad' => 20],
                    ['nombre' => 'Piel de naranja', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 18. CAIPIRNHA
            // ========================================
            [
                'nombre' => 'CAIPIRNHA',
                'ingredientes' => [
                    ['nombre' => 'Cachaça Jamel', 'cantidad' => 60],
                    ['nombre' => 'Azucar blanca', 'cantidad' => 20],
                    ['nombre' => 'Limon tahiti', 'cantidad' => 35],
                ]
            ],

            // ========================================
            // 19. GIN PASION
            // ========================================
            [
                'nombre' => 'GIN PASION',
                'ingredientes' => [
                    ['nombre' => "Gin Gordon's Pink", 'cantidad' => 60],
                    ['nombre' => 'Tónica 1976 Indi', 'cantidad' => 100],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 45],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 15],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 2],
                    ['nombre' => 'Piña oro miel', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 20. GYN TONIC LYCHEE ROSA
            // ========================================
            [
                'nombre' => 'GYN TONIC LYCHEE ROSA',
                'ingredientes' => [
                    ['nombre' => "Gin Gordon's Pink", 'cantidad' => 60],
                    ['nombre' => 'Syrup De Lychee Real', 'cantidad' => 30],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 15],
                    ['nombre' => 'Tónica 1976 Indi', 'cantidad' => 100],
                    ['nombre' => 'Lyches', 'cantidad' => 5],
                    ['nombre' => 'Mora', 'cantidad' => 5],
                ]
            ],
            // ========================================
            // 21. GYN CLASSIC
            // ========================================
            [
                'nombre' => 'GYN CLASSIC',
                'ingredientes' => [
                    ['nombre' => "Gin Gordon's Pink", 'cantidad' => 60],
                    ['nombre' => 'Pepino cohombro', 'cantidad' => 20],
                    ['nombre' => 'Tónica 1976 Indi', 'cantidad' => 100],
                    ['nombre' => 'Romero', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 22. GYN ISABELLA
            // ========================================
            [
                'nombre' => 'GYN ISABELLA',
                'ingredientes' => [
                    ['nombre' => "Gin Gordon's Pink", 'cantidad' => 60],
                    ['nombre' => 'Syrup De Uva Isabella', 'cantidad' => 45],
                    ['nombre' => 'Tónica 1976 Indi', 'cantidad' => 100],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 15],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 23. AURA VERDE
            // ========================================
            [
                'nombre' => 'AURA VERDE',
                'ingredientes' => [
                    ['nombre' => 'Syrup De Lychee Real', 'cantidad' => 45],
                    ['nombre' => 'Albahaca Fresca', 'cantidad' => 7],
                    ['nombre' => 'Pepino cohombro', 'cantidad' => 58],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Soda 250 ml', 'cantidad' => 0.24],
                ]
            ],
            // ========================================
            // 24. ECO DEL JARDÍN
            // ========================================
            [
                'nombre' => 'ECO DEL JARDÍN',
                'ingredientes' => [
                    ['nombre' => 'Mora', 'cantidad' => 10],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 10],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Agua Tónica Ginger Beer', 'cantidad' => 100],
                    ['nombre' => 'Miel de agave', 'cantidad' => 15],
                ]
            ],
            // ========================================
            // 25. AGUA DE SOL
            // ========================================
            [
                'nombre' => 'AGUA DE SOL',
                'ingredientes' => [
                    ['nombre' => 'Syrup De Piña Real', 'cantidad' => 30],
                    ['nombre' => 'Juniper De Coco', 'cantidad' => 100],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Perfume de vainilla negra', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 26. SUSURRO CRITICO
            // ========================================
            [
                'nombre' => 'SUSURRO CRITICO',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 30],
                    ['nombre' => 'Mezclador De Toronja', 'cantidad' => 100],
                ]
            ],
            // ========================================
            // 27. TÉ HELADO HIERBABUENA
            // ========================================
            [
                'nombre' => 'TÉ HELADO HIERBABUENA',
                'ingredientes' => [
                    ['nombre' => 'Hierbabuena', 'cantidad' => 10],
                    ['nombre' => 'Té de limon', 'cantidad' => 300],
                    ['nombre' => 'Azucar blanca', 'cantidad' => 5],
                ]
            ],

            // ========================================
            // 28. SANGRÍA TINTO JARRA
            // ========================================
            [
                'nombre' => 'SANGRÍA TINTO JARRA',
                'ingredientes' => [
                    ['nombre' => 'Vino Rosadela Tinto', 'cantidad' => 750],
                    ['nombre' => 'Tequila Bestia', 'cantidad' => 60],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 30],
                    ['nombre' => 'Agua Tónica Ginger Beer', 'cantidad' => 200],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Uchuva', 'cantidad' => 100],
                    ['nombre' => 'Manzana verde', 'cantidad' => 100],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 120],
                    ['nombre' => 'Hielo seco', 'cantidad' => 10],
                ]
            ],
            // ========================================
            // 29. SANGRÍA TINTO COPA
            // ========================================
            [
                'nombre' => 'SANGRÍA TINTO COPA',
                'ingredientes' => [
                    ['nombre' => 'Vino Rosadela Tinto', 'cantidad' => 150],
                    ['nombre' => 'Tequila Bestia', 'cantidad' => 15],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 15],
                    ['nombre' => 'Agua Tónica Ginger Beer', 'cantidad' => 45],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 7.5],
                    ['nombre' => 'Uchuva', 'cantidad' => 20],
                    ['nombre' => 'Manzana verde', 'cantidad' => 20],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 22.5],
                ]
            ],
            // ========================================
            // 30. SANGRÍA ROSÉ JARRA
            // ========================================
            [
                'nombre' => 'SANGRÍA ROSÉ JARRA',
                'ingredientes' => [
                    ['nombre' => 'Vino Rosadela Rosé', 'cantidad' => 750],
                    ['nombre' => 'Vodka orlikoff', 'cantidad' => 60],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 30],
                    ['nombre' => 'Agua Tónica Ginger Beer', 'cantidad' => 200],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Fresa', 'cantidad' => 5],
                    ['nombre' => 'Uva Blanca', 'cantidad' => 100],
                    ['nombre' => 'Syrup De Flor De Jamaica', 'cantidad' => 120],
                ]
            ],
            // ========================================
            // 31. SANGRÍA ROSÉ COPA
            // ========================================
            [
                'nombre' => 'SANGRÍA ROSÉ COPA',
                'ingredientes' => [
                    ['nombre' => 'Vino Rosadela Rosé', 'cantidad' => 150],
                    ['nombre' => 'Vodka orlikoff', 'cantidad' => 15],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 15],
                    ['nombre' => 'Agua Tónica Ginger Beer', 'cantidad' => 45],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 7.5],
                    ['nombre' => 'Fresa', 'cantidad' => 20],
                    ['nombre' => 'Uva Blanca', 'cantidad' => 20],
                    ['nombre' => 'Syrup De Flor De Jamaica', 'cantidad' => 22.5],
                ]
            ],
            // ========================================
            // 32. SANGRÍA BLANCA JARRA
            // ========================================
            [
                'nombre' => 'SANGRÍA BLANCA JARRA',
                'ingredientes' => [
                    ['nombre' => 'Vino Rosadela Blanco', 'cantidad' => 750],
                    ['nombre' => 'Ron cortez blanco', 'cantidad' => 60],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 30],
                    ['nombre' => 'Sparkling Tamarindo Mil 976', 'cantidad' => 200],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Fresa', 'cantidad' => 5],
                    ['nombre' => 'Manzana verde', 'cantidad' => 100],
                    ['nombre' => 'Syrup De Uchuva', 'cantidad' => 120],
                ]
            ],
            // ========================================
            // 33. SANGRÍA BLANCA COPA
            // ========================================
            [
                'nombre' => 'SANGRÍA BLANCA COPA',
                'ingredientes' => [
                    ['nombre' => 'Vino Rosadela Blanco', 'cantidad' => 150],
                    ['nombre' => 'Ron cortez blanco', 'cantidad' => 15],
                    ['nombre' => 'Convier triple sec', 'cantidad' => 15],
                    ['nombre' => 'Sparkling Tamarindo Mil 976', 'cantidad' => 45],
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 7.5],
                    ['nombre' => 'Fresa', 'cantidad' => 20],
                    ['nombre' => 'Manzana verde', 'cantidad' => 20],
                    ['nombre' => 'Syrup De Uchuva', 'cantidad' => 22.5],
                ]
            ],
            // ========================================
            // 34. BRETAÑA MARACUYA UCHUVA
            // ========================================
            [
                'nombre' => 'BRETAÑA MARACUYA UCHUVA',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 60],
                    ['nombre' => 'Syrup De Uchuva', 'cantidad' => 60],
                    ['nombre' => 'Soda 250 ml', 'cantidad' => 1],
                    ['nombre' => 'Romero', 'cantidad' => 2],
                    ['nombre' => 'Toronja', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 35. BRETAÑA UVA ISABELLA
            // ========================================
            [
                'nombre' => 'BRETAÑA UVA ISABELLA',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Uva Isabella', 'cantidad' => 60],
                    ['nombre' => 'Sirope simple', 'cantidad' => 30],
                    ['nombre' => 'Soda 250 ML', 'cantidad' => 1],
                    ['nombre' => 'Uva isabella', 'cantidad' => 2],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 36. BRETAÑA KIWI JAMAICA
            // ========================================
            [
                'nombre' => 'BRETAÑA KIWI JAMAICA',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Sirope De Kiwi', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Flor De Jamaica', 'cantidad' => 60],
                    ['nombre' => 'Soda 250 ML', 'cantidad' => 1],
                    ['nombre' => 'Hierbabuena', 'cantidad' => 5],
                    ['nombre' => 'Kiwi', 'cantidad' => 2],
                ]
            ],
            // ========================================
            // 37. BRETAÑA SODA UVA BLANCA ROMERO
            // ========================================
            [
                'nombre' => 'BRETAÑA SODA UVA BLANCA ROMERO',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Uva Blanca', 'cantidad' => 90],
                    ['nombre' => 'Soda 250 ML', 'cantidad' => 1],
                    ['nombre' => 'Romero', 'cantidad' => 2],
                    ['nombre' => 'Uva Blanca', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 38. BRETAÑA SODA LYCHEE ROSA
            // ========================================
            [
                'nombre' => 'BRETAÑA SODA LYCHEE ROSA',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Lychee Real', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Flor De Jamaica', 'cantidad' => 30],
                    ['nombre' => 'Soda 250 ML', 'cantidad' => 1],
                ]
            ],
            // ========================================
            // 39. BRETAÑA SODA PIÑA MARACUYA
            // ========================================
            [
                'nombre' => 'BRETAÑA SODA PIÑA MARACUYA',
                'ingredientes' => [
                    ['nombre' => 'Rend zumo de limón', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Piña Real', 'cantidad' => 30],
                    ['nombre' => 'Syrup De Maracuyá', 'cantidad' => 30],
                    ['nombre' => 'Soda 250 ML', 'cantidad' => 1],
                ]
            ],
        ];

        // ========================================
        // Crear cada coctel
        // ========================================
        foreach ($cocteles as $coctelData) {
            $this->crearCoctel(
                $coctelData['nombre'],
                $categoriaBebidas->id,
                $unidadCompraId,
                $coctelData['ingredientes']
            );
        }

        $this->command->info('✅ Todos los cocteles fueron creados exitosamente!');
    }

    /**
     * Crear un coctel (producto tipo 'venta') con sus ingredientes
     * Usa la relación del modelo Producto
     */
    private function crearCoctel(string $nombre, int $categoriaId, int $unidadCompraId, array $ingredientes): void
    {
        $sedeId = DB::table('sedes')->orderBy('id', 'asc')->value('id') ?? 1;

        // Buscar si ya existe o crear el coctel
        $coctel = Producto::firstOrCreate(
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

        $ingredientesIds = [];

        foreach ($ingredientes as $ingData) {
            $nombreBuscado = trim($ingData['nombre']);
            
            $ingrediente = Producto::withoutGlobalScope('sede')
                ->whereRaw('LOWER(TRIM(nombre)) = ?', [strtolower($nombreBuscado)])
                ->first();

            if ($ingrediente) {
                $ingredientesIds[$ingrediente->id] = [
                    'cantidad' => $ingData['cantidad'],
                    'nota' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $this->command->warn("⚠️ Ingrediente no encontrado: '{$ingData['nombre']}' para coctel '{$nombre}'");
                $this->command->warn("   💡 Debes crearlo primero como insumo o subensamble.");
            }
        }

        if (!empty($ingredientesIds)) {
            $coctel->ingredientes()->sync($ingredientesIds);
            $this->command->info("🍹 Coctel '{$nombre}' creado con " . count($ingredientesIds) . " ingredientes");
        } else {
            $this->command->warn("⚠️ No se pudo crear el coctel '{$nombre}' porque no tiene ingredientes válidos.");
        }
    }
}