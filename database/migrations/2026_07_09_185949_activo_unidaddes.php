<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unidades_medida', function (Blueprint $table) {
            // Agregar campo activo
            if (!Schema::hasColumn('unidades_medida', 'activo')) {
                $table->boolean('activo')->default(true)->after('abreviatura');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unidades_medida', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};