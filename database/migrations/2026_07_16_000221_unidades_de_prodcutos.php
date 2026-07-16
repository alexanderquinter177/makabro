<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Intercambiar nombres correctamente
            // 1. Renombrar unidad_medida_id a unidad_compra_id (porque realmente es compra)
            $table->renameColumn('unidad_medida_id', 'unidad_compra_id_temp');
            
            // 2. Renombrar unidad_compra_id a unidad_uso_id (porque realmente es uso)
            $table->renameColumn('unidad_compra_id', 'unidad_medida_id');
            
            // 3. Renombrar el temporal a unidad_compra_id
            $table->renameColumn('unidad_compra_id_temp', 'unidad_compra_id');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertir el intercambio
            $table->renameColumn('unidad_compra_id', 'unidad_compra_id_temp');
            $table->renameColumn('unidad_medida_id', 'unidad_compra_id');
            $table->renameColumn('unidad_compra_id_temp', 'unidad_medida_id');
        });
    }
};