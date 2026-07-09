<?php
// 2026_01_01_000013_create_inventarios_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_inventario');
            $table->string('area')->nullable(); // cocina, barra, administración
            $table->string('tipo_inventario')->default('diario'); // diario, mensual, completo
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->text('notas')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
            
            // Un registro de inventario es inmutable
            $table->unique(['sede_id', 'fecha_inventario', 'area', 'tipo_inventario']);
            $table->index(['sede_id', 'fecha_inventario']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}