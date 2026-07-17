<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // ========================================
            // 1. CREAR COLUMNA TEMPORAL
            // ========================================
            if (Schema::hasColumn('productos', 'unidad_compra_id')) {
                $table->renameColumn('unidad_compra_id', 'unidad_compra_id_old');
                echo "✅ Renombrado: unidad_compra_id -> unidad_compra_id_old\n";
            }

            // ========================================
            // 2. RENOMBRAR unidad_medida_id -> unidad_compra_id
            // ========================================
            if (Schema::hasColumn('productos', 'unidad_medida_id')) {
                $table->renameColumn('unidad_medida_id', 'unidad_compra_id');
                echo "✅ Renombrado: unidad_medida_id -> unidad_compra_id\n";
            }

            // ========================================
            // 3. RENOMBRAR la vieja unidad_compra_id a unidad_medida_id
            // ========================================
            if (Schema::hasColumn('productos', 'unidad_compra_id_old')) {
                $table->renameColumn('unidad_compra_id_old', 'unidad_medida_id');
                echo "✅ Renombrado: unidad_compra_id_old -> unidad_medida_id\n";
            }
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertir el intercambio
            if (Schema::hasColumn('productos', 'unidad_medida_id')) {
                $table->renameColumn('unidad_medida_id', 'unidad_compra_id_old');
                echo "✅ Revertido: unidad_medida_id -> unidad_compra_id_old\n";
            }

            if (Schema::hasColumn('productos', 'unidad_compra_id')) {
                $table->renameColumn('unidad_compra_id', 'unidad_medida_id');
                echo "✅ Revertido: unidad_compra_id -> unidad_medida_id\n";
            }

            if (Schema::hasColumn('productos', 'unidad_compra_id_old')) {
                $table->renameColumn('unidad_compra_id_old', 'unidad_compra_id');
                echo "✅ Revertido: unidad_compra_id_old -> unidad_compra_id\n";
            }
        });
    }
};