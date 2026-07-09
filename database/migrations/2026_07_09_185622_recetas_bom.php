<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas_bom', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('producto_padre_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            $table->foreignId('producto_hijo_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Cantidad necesaria del ingrediente
            $table->decimal('cantidad', 15, 4);
            
            // Nota u observación del ingrediente
            $table->string('nota', 255)->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices y restricciones
            $table->unique(['producto_padre_id', 'producto_hijo_id'], 'unique_receta_componente');
            $table->index(['producto_padre_id']);
            $table->index(['producto_hijo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas_bom');
    }
};