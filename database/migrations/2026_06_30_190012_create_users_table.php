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
        Schema::table('users', function (Blueprint $table) {
            // Usamos ->after('columna') para ubicar los campos ordenadamente en la base de datos
            $table->foreignId('sede_id')->nullable()->after('id')->constrained('sedes')->onDelete('set null');
            
            $table->string('cargo')->nullable()->after('password'); 
            $table->string('telefono')->nullable()->after('cargo');
            $table->boolean('activo')->default(true)->after('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si revertimos (rollback), debemos soltar primero la llave foránea y luego las columnas
            $table->dropForeign(['sede_id']);
            $table->dropColumn(['sede_id', 'cargo', 'telefono', 'activo']);
        });
    }
};