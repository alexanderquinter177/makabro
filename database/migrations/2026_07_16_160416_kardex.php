<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kardex_movimientos', function (Blueprint $table) {
            // Verificar si las columnas existen antes de agregarlas
            if (!Schema::hasColumn('kardex_movimientos', 'saldo_anterior')) {
                $table->decimal('saldo_anterior', 15, 4)->nullable()->after('cantidad');
            }
            
            if (!Schema::hasColumn('kardex_movimientos', 'saldo_despues')) {
                $table->decimal('saldo_despues', 15, 4)->nullable()->after('saldo_anterior');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kardex_movimientos', function (Blueprint $table) {
            $table->dropColumn(['saldo_anterior', 'saldo_despues']);
        });
    }
};