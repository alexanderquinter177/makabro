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
        Schema::table('compra_items', function (Blueprint $table) {
            if (!Schema::hasColumn('compra_items', 'unidad_compra')) {
                $table->string('unidad_compra', 50)->nullable()->after('producto_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compra_items', function (Blueprint $table) {
            if (Schema::hasColumn('compra_items', 'unidad_compra')) {
                $table->dropColumn('unidad_compra');
            }
        });
    }
};
