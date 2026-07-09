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
            // Agregar campo cédula (único para evitar duplicados)
            $table->string('cedula', 20)->unique()->after('id');
            
            // Hacer email nullable (opcional, para compatibilidad)
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cedula');
            $table->string('email')->nullable(false)->change();
        });
    }
};