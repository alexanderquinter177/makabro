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
                    $this->command->info("✅ Eliminada FK: {$fk->CONSTRAINT_NAME}");
                } catch (\Exception $e) {
                    $this->command->warn("⚠️ No se pudo eliminar: {$fk->CONSTRAINT_NAME}");
                }
            }

            // ========================================
            // 2. ELIMINAR LA COLUMNA
            // ========================================
            if (Schema::hasColumn('productos', 'unidad_medida_id')) {
                $table->dropColumn('unidad_medida_id');
                $this->command->info("✅ Eliminada columna: unidad_medida_id");
            }

            // ========================================
            // 3. ELIMINAR OTRAS COLUMNAS
            // ========================================
            if (Schema::hasColumn('productos', 'factor_conversion')) {
                $table->dropColumn('factor_conversion');
                $this->command->info("✅ Eliminada columna: factor_conversion");
            }

            if (Schema::hasColumn('productos', 'unidad_base')) {
                $table->dropColumn('unidad_base');
                $this->command->info("✅ Eliminada columna: unidad_base");
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
                $table->foreignId('unidad_medida_id')
                    ->nullable()
                    ->constrained('unidades_medida')
                    ->onDelete('set null');
                $this->command->info("✅ Restaurada columna: unidad_medida_id");
            }

            if (!Schema::hasColumn('productos', 'factor_conversion')) {
                $table->decimal('factor_conversion', 10, 4)->nullable();
                $this->command->info("✅ Restaurada columna: factor_conversion");
            }

            if (!Schema::hasColumn('productos', 'unidad_base')) {
                $table->string('unidad_base')->nullable();
                $this->command->info("✅ Restaurada columna: unidad_base");
            }
        });
    }
};