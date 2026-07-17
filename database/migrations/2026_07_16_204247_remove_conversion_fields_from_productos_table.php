<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // 1. Dropear llave foránea de unidad_medida_id
            try {
                $table->dropForeign(['unidad_medida_id']);
            } catch (\Exception $e) {
                // Si no existía o el nombre era distinto, ignorar
            }

            // 2. Eliminar las columnas de la tabla productos
            $table->dropColumn([
                'factor_conversion',
                'unidad_medida_id',
                'costo_unitario'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('unidad_medida_id')
                  ->nullable()
                  ->constrained('unidades_medida')
                  ->nullOnDelete();
                  
            $table->decimal('factor_conversion', 12, 4)
                  ->nullable()
                  ->default(1);
                  
            $table->decimal('costo_unitario', 12, 4)
                  ->nullable();
        });
    }
};
