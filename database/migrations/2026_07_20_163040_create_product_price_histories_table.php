<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_price_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();

            $table->foreignId('proveedor_id')
                ->nullable()
                ->constrained('proveedores')
                ->nullOnDelete();

            $table->foreignId('compra_id')
                ->nullable()
                ->constrained('compras')
                ->nullOnDelete();

            $table->decimal('precio_anterior', 15, 4)->default(0);
            $table->decimal('precio_nuevo', 15, 4);

            // Unidad en la que se expresan los precios (base del catálogo)
            $table->string('unidad_base', 20)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_histories');
    }
};
