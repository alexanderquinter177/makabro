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
        Schema::create('cargas_historial', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('nombre_recibe');
            $table->string('tipo');
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('cargas_productos_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carga_historial_id')
                ->constrained('cargas_historial')
                ->onDelete('cascade');
            $table->string('codigo');
            $table->string('nombre_producto');
            $table->string('categoria');
            $table->string('tipo_producto');
            $table->string('unidad_medida');
            $table->decimal('cantidad', 12, 2)->default(0);
            $table->decimal('precio', 15, 2)->default(0);
            $table->decimal('total_linea', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargas_productos_historial');
        Schema::dropIfExists('cargas_historial');
    }
};
