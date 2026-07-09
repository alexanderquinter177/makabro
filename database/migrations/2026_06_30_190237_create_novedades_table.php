<?php
// 2026_01_01_000015_create_novedades_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovedadesTable extends Migration
{
    public function up()
    {
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('usuario_id')->constrained('users'); // quien registra
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tipo'); // caída/quiebre, quemado, vencimiento, daño, devolución, pérdida/robo
            $table->string('area'); // cocina, barra, administración
            $table->string('tipo_afectado')->nullable(); // plato, producto, mueble
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->decimal('cantidad', 15, 2)->nullable();
            $table->decimal('valor_costo', 15, 2)->default(0); // costo de producción
            $table->decimal('valor_cobro', 15, 2)->default(0); // monto a cobrar al empleado (si aplica)
            $table->string('estado_cobro')->default('pendiente'); // si, no, pendiente
            $table->text('descripcion')->nullable();
            $table->string('evidencia_imagen')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['sede_id', 'tipo']);
            $table->index('estado_cobro');
        });
    }

    public function down()
    {
        Schema::dropIfExists('novedades');
    }
}