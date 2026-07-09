<?php
// 2026_01_01_000016_create_auditorias_table.php (para el módulo futuro)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditoriasTable extends Migration
{
    public function up()
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_auditoria');
            $table->string('tipo_auditoria'); // sorpresa, programada, arqueo
            $table->string('estado')->default('pendiente'); // pendiente, en_progreso, completada
            $table->text('hallazgos')->nullable();
            $table->decimal('diferencia_encontrada', 15, 2)->default(0);
            $table->text('acciones_tomadas')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auditorias');
    }
}