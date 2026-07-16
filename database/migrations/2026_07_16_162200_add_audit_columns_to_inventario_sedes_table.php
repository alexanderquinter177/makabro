<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventario_sedes', function (Blueprint $table) {
            if (!Schema::hasColumn('inventario_sedes', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('inventario_sedes', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('inventario_sedes', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventario_sedes', function (Blueprint $table) {
            // Nota: En PostgreSQL, para eliminar llaves foráneas primero hay que eliminar la restricción,
            // pero para simplificar la reversión en desarrollo local:
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        });
    }
};
