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
        // Primero, asegurarnos de migrar los datos existentes
        // (opcional, si ya tienes datos)
        Schema::table('users', function (Blueprint $table) {
            // Si existe la columna, la removemos
            if (Schema::hasColumn('users', 'sede_id')) {
                $table->dropForeign(['sede_id']);
                $table->dropColumn('sede_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sede_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};