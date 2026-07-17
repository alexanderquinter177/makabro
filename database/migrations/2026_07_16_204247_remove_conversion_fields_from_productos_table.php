<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Verificar si la FK existe antes de eliminarla
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'productos' 
                AND CONSTRAINT_NAME = 'productos_unidad_medida_id_foreign'
            ");

            if (!empty($foreignKeys)) {
                $table->dropForeign('productos_unidad_medida_id_foreign');
            }

            // Eliminar la columna si existe
            if (Schema::hasColumn('productos', 'unidad_medida_id')) {
                $table->dropColumn('unidad_medida_id');
            }

            // Eliminar otras columnas
            if (Schema::hasColumn('productos', 'factor_conversion')) {
                $table->dropColumn('factor_conversion');
            }

            if (Schema::hasColumn('productos', 'unidad_base')) {
                $table->dropColumn('unidad_base');
            }
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertir los cambios
            if (!Schema::hasColumn('productos', 'unidad_medida_id')) {
                $table->foreignId('unidad_medida_id')->nullable()->constrained('unidades_medida');
            }
            if (!Schema::hasColumn('productos', 'factor_conversion')) {
                $table->decimal('factor_conversion', 10, 4)->nullable();
            }
            if (!Schema::hasColumn('productos', 'unidad_base')) {
                $table->string('unidad_base')->nullable();
            }
        });
    }
};