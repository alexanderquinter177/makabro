<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            
            // Ajustado para evitar errores si se borra una categoría
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            
            $table->string('codigo')->unique(); // SKU
            $table->string('nombre');
            
            // NUEVO: El núcleo de la explosión recursiva BOM
            $table->enum('tipo', ['venta', 'subensamble', 'insumo'])->default('insumo');
            
            $table->foreignId('unidad_medida_id')->constrained('unidades_medida');
            $table->decimal('precio_unitario', 15, 2)->default(0);
            $table->string('proveedor_habitual')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('notas')->nullable();
            
            // Auditoría y SoftDeletes
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            // Índices para optimizar búsquedas en Filament
            $table->index('codigo');
            $table->index('nombre');
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
}