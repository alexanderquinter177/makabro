<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kardex_movimientos', function (Blueprint $table) {
            // Agregar columna deleted_at para SoftDeletes
            if (!Schema::hasColumn('kardex_movimientos', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kardex_movimientos', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};