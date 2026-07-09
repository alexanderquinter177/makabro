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
        Schema::create('inventario_sedes', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();

            // Control numérico (Usamos decimal(12,2) para soportar gramos, mililitros, etc.)
            $table->decimal('cantidad_actual', 12, 2)->default(0);
            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->decimal('stock_maximo', 12, 2)->default(0);
            $table->decimal('punto_reorden', 12, 2)->default(0);

            // Restricción de unicidad: Un producto solo puede tener un registro de stock por sede
            $table->unique(['sede_id', 'producto_id']);

            // Auditoría y SoftDeletes (Para mantener tu estándar)
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_sedes');
    }
};