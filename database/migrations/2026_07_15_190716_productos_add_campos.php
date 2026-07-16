<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Cambiar nombre de precio_unitario a precio_compra
            $table->renameColumn('precio_unitario', 'precio_compra');
            
            // Agregar nuevos campos
            $table->foreignId('unidad_compra_id')
                  ->nullable()
                  ->after('unidad_medida_id')
                  ->constrained('unidades_medida')
                  ->nullOnDelete();
            
            $table->decimal('factor_conversion', 12, 4)
                  ->nullable()
                  ->default(1)
                  ->after('precio_compra')
                  ->comment('Factor para convertir unidad de compra a unidad de uso');
            
            $table->decimal('costo_unitario', 12, 4)
                  ->nullable()
                  ->after('factor_conversion')
                  ->comment('Costo por unidad de uso (calculado automáticamente)');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->renameColumn('precio_compra', 'precio_unitario');
            
            $table->dropForeign(['unidad_compra_id']);
            $table->dropColumn(['unidad_compra_id', 'factor_conversion', 'costo_unitario']);
        });
    }
};