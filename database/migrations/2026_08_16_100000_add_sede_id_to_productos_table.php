<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Quitar la restricción unique de código único global
        Schema::table('productos', function (Blueprint $table) {
            try {
                $table->dropUnique('productos_codigo_unique');
            } catch (\Throwable $e) {
                try {
                    $table->dropUnique(['codigo']);
                } catch (\Throwable $e2) {
                    // Ignorar si ya fue removido
                }
            }
        });

        // 2. Agregar columna sede_id si no existe aún
        if (!Schema::hasColumn('productos', 'sede_id')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->foreignId('sede_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('sedes')
                    ->cascadeOnDelete();
            });
        }

        // 3. Data Migration: Clonar productos y sus Recetas/Ingredientes (BOM) por cada sede
        DB::transaction(function () {
            $sedes = DB::table('sedes')->orderBy('id', 'asc')->get();

            if ($sedes->isEmpty()) {
                return;
            }

            $primeraSede = $sedes->first();
            $otrasSedes = $sedes->slice(1);

            // Asignar sede_id a los productos que no tengan sede asignada aún
            DB::table('productos')
                ->whereNull('sede_id')
                ->update(['sede_id' => $primeraSede->id]);

            // Obtener los productos base de la primera sede
            $productosOriginales = DB::table('productos')
                ->where('sede_id', $primeraSede->id)
                ->get();

            $now = now();

            foreach ($otrasSedes as $sede) {
                $mapOldToNewId = [];

                foreach ($productosOriginales as $producto) {
                    // Verificar si ya existe este producto en esa sede
                    $existente = DB::table('productos')
                        ->where('sede_id', $sede->id)
                        ->where('codigo', $producto->codigo)
                        ->first();

                    if ($existente) {
                        $mapOldToNewId[$producto->id] = $existente->id;
                    } else {
                        $nuevoProducto = (array) $producto;

                        // Eliminar el ID original para permitir autoincremento
                        unset($nuevoProducto['id']);

                        // Asignar el sede_id correspondiente y refrescar timestamps
                        $nuevoProducto['sede_id'] = $sede->id;
                        $nuevoProducto['created_at'] = $now;
                        $nuevoProducto['updated_at'] = $now;

                        $nuevoId = DB::table('productos')->insertGetId($nuevoProducto);
                        $mapOldToNewId[$producto->id] = $nuevoId;
                    }
                }

                // Clonar y remapear la estructura de Recetas / Ingredientes (BOM)
                if (Schema::hasTable('recetas_bom')) {
                    $recetasOriginales = DB::table('recetas_bom')
                        ->whereIn('producto_padre_id', array_keys($mapOldToNewId))
                        ->get();

                    foreach ($recetasOriginales as $receta) {
                        $nuevoPadreId = $mapOldToNewId[$receta->producto_padre_id] ?? null;
                        $nuevoHijoId  = $mapOldToNewId[$receta->producto_hijo_id] ?? null;

                        if ($nuevoPadreId && $nuevoHijoId) {
                            $existeBom = DB::table('recetas_bom')
                                ->where('producto_padre_id', $nuevoPadreId)
                                ->where('producto_hijo_id', $nuevoHijoId)
                                ->exists();

                            if (!$existeBom) {
                                DB::table('recetas_bom')->insert([
                                    'producto_padre_id' => $nuevoPadreId,
                                    'producto_hijo_id'  => $nuevoHijoId,
                                    'cantidad'          => $receta->cantidad,
                                    'nota'              => $receta->nota,
                                    'created_by'        => $receta->created_by,
                                    'updated_by'        => $receta->updated_by,
                                    'deleted_by'        => $receta->deleted_by,
                                    'created_at'        => $now,
                                    'updated_at'        => $now,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        // 4. Modificar la columna sede_id a NOT NULL y agregar la restricción unique por [sede_id, codigo]
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('sede_id')->nullable(false)->change();
            
            // Índice compuesto único para que el código sea único DENTRO de cada sede
            try {
                $table->unique(['sede_id', 'codigo']);
            } catch (\Throwable $e) {
                // Si ya existe la llave compuesta, continuar
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            try {
                $table->dropUnique(['sede_id', 'codigo']);
            } catch (\Throwable $e) {}

            try {
                $table->unique('codigo');
            } catch (\Throwable $e) {}

            $table->dropForeign(['sede_id']);
            $table->dropColumn('sede_id');
        });
    }
};
