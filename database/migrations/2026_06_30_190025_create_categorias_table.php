<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            
            // Datos principales
            $table->string('nombre', 255);
            $table->string('slug', 255)->unique();
            $table->string('color', 50)->nullable();
            $table->text('descripcion')->nullable();
            
            // Estado
            $table->boolean('activo')->default(true);
            
            // Auditoría (SoftDeletes + AuditSignature)
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['slug']);
            $table->index(['activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};