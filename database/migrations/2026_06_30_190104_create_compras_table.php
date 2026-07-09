<?php
// 2026_01_01_000007_create_compras_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users'); // quien registra
            $table->string('numero_factura');
            $table->date('fecha_factura');
            $table->date('fecha_registro');
            $table->string('forma_pago')->nullable(); // efectivo, transferencia, etc.
            $table->string('tipo_compra')->nullable(); // tipo/categoría de compra
            $table->string('recibido_por')->nullable(); // quién recibe
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('imagen_factura')->nullable(); // ruta de la foto
            $table->text('notas')->nullable();
            $table->boolean('registro_tardio')->default(false); // alerta de demora
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['sede_id', 'fecha_factura']);
            $table->index('numero_factura');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras');
    }
}