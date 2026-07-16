<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kardex_movimientos', function (Blueprint $table) {
            // 1. Costo unitario
            if (!Schema::hasColumn('kardex_movimientos', 'costo_unitario')) {
                $table->decimal('costo_unitario', 15, 2)->nullable()->after('saldo_despues');
            }
            
            // 2. Costo total
            if (!Schema::hasColumn('kardex_movimientos', 'costo_total')) {
                $table->decimal('costo_total', 15, 2)->nullable()->after('costo_unitario');
            }
            
            // 3. SoftDeletes (deleted_at)
            if (!Schema::hasColumn('kardex_movimientos', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kardex_movimientos', function (Blueprint $table) {
            $table->dropColumn([
                'costo_unitario',
                'costo_total',
                'deleted_at'
            ]);
        });
    }
};