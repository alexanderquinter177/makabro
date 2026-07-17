<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // ========================================
            // 1. ELIMINAR TODAS LAS FK QUE USAN unidad_medida_id
            // ========================================
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'productos' 
                AND COLUMN_NAME = 'unidad_medida_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                try {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                    echo "✅ Eliminada FK: {$fk->CONSTRAINT_NAME}\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo eliminar: {$fk->CONSTRAINT_NAME} - {$e->getMessage()}\n";
                }
            }

            // ========================================
            // 2. ELIMINAR LA COLUMNA unidad_medida_id
            // ========================================
            if (Schema::hasColumn('productos', 'unidad_medida_id')) {
                try {
                    $table->dropColumn('unidad_medida_id');
                    echo "✅ Eliminada columna: unidad_medida_id\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo eliminar unidad_medida_id: {$e->getMessage()}\n";
                }
            }

            // ========================================
            // 3. ELIMINAR OTRAS COLUMNAS
            // ========================================
            if (Schema::hasColumn('productos', 'factor_conversion')) {
                try {
                    $table->dropColumn('factor_conversion');
                    echo "✅ Eliminada columna: factor_conversion\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo eliminar factor_conversion: {$e->getMessage()}\n";
                }
            }

            if (Schema::hasColumn('productos', 'unidad_base')) {
                try {
                    $table->dropColumn('unidad_base');
                    echo "✅ Eliminada columna: unidad_base\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo eliminar unidad_base: {$e->getMessage()}\n";
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // ========================================
            // 1. RESTAURAR COLUMNAS
            // ========================================
            if (!Schema::hasColumn('productos', 'unidad_medida_id')) {
                try {
                    $table->foreignId('unidad_medida_id')
                        ->nullable()
                        ->constrained('unidades_medida')
                        ->onDelete('set null');
                    echo "✅ Restaurada columna: unidad_medida_id\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo restaurar unidad_medida_id: {$e->getMessage()}\n";
                }
            }

            if (!Schema::hasColumn('productos', 'factor_conversion')) {
                try {
                    $table->decimal('factor_conversion', 10, 4)->nullable();
                    echo "✅ Restaurada columna: factor_conversion\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo restaurar factor_conversion: {$e->getMessage()}\n";
                }
            }

            if (!Schema::hasColumn('productos', 'unidad_base')) {
                try {
                    $table->string('unidad_base')->nullable();
                    echo "✅ Restaurada columna: unidad_base\n";
                } catch (\Exception $e) {
                    echo "⚠️ No se pudo restaurar unidad_base: {$e->getMessage()}\n";
                }
            }
        });
    }
};