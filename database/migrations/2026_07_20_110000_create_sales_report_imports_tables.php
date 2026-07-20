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
        Schema::create('sales_report_imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sede_id')->nullable();
            $table->string('date_range');
            $table->string('file_name');
            $table->timestamps();

            $table->foreign('sede_id')->references('id')->on('sedes')->nullOnDelete();
        });

        Schema::create('sales_report_import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('sales_report_imports')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('punto_operacion')->nullable();
            $table->string('grupo')->nullable();
            $table->string('producto_nombre');
            $table->string('unidad')->nullable();
            $table->decimal('cortesia', 15, 4)->default(0);
            $table->decimal('hora_feliz', 15, 4)->default(0);
            $table->decimal('consumo', 15, 4)->default(0);
            $table->decimal('baja_dano', 15, 4)->default(0);
            $table->decimal('cantidad_venta', 15, 4)->default(0);
            $table->decimal('venta_bruta', 15, 4)->default(0);
            $table->decimal('descuento', 15, 4)->default(0);
            $table->decimal('venta_neta', 15, 4)->default(0);
            $table->decimal('impuesto', 15, 4)->default(0);
            $table->decimal('total', 15, 4)->default(0);
            $table->decimal('v_unitario', 15, 4)->default(0);
            $table->decimal('porcentaje', 15, 4)->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('productos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_report_import_items');
        Schema::dropIfExists('sales_report_imports');
    }
};
