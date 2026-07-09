<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================
        // TABLA: inventario_items (Productos contados en inventario)
        // ============================================
        Schema::create('inventario_items', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('inventario_id')
                  ->constrained('inventarios')
                  ->onDelete('cascade');
            
            $table->foreignId('producto_id')
                  ->constrained('productos');
            
            // Cantidades
            $table->decimal('cantidad_contada', 15, 2);
            $table->decimal('cantidad_sistema', 15, 2)->nullable();
            $table->decimal('diferencia', 15, 2)->nullable();
            
            // Valores
            $table->decimal('costo_unitario', 15, 2)->default(0);
            $table->decimal('valor_total', 15, 2)->default(0);
            
            // Observación
            $table->text('observacion')->nullable();
            
            // Auditoría (SoftDeletes + AuditSignature)
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['inventario_id', 'producto_id']);
            $table->index(['producto_id']);
            $table->index(['diferencia']);
        });

      
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_items');
    }
};