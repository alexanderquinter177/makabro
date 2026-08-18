<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargas_historial', function (Blueprint $table) {
            if (!Schema::hasColumn('cargas_historial', 'sede_id')) {
                $table->foreignId('sede_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('sedes')
                    ->nullOnDelete();
            }
        });

        // Asignar primera sede a los registros existentes que no tengan sede
        $primeraSede = DB::table('sedes')->orderBy('id', 'asc')->first();
        if ($primeraSede) {
            DB::table('cargas_historial')
                ->whereNull('sede_id')
                ->update(['sede_id' => $primeraSede->id]);
        }
    }

    public function down(): void
    {
        Schema::table('cargas_historial', function (Blueprint $table) {
            if (Schema::hasColumn('cargas_historial', 'sede_id')) {
                $table->dropForeign(['sede_id']);
                $table->dropColumn('sede_id');
            }
        });
    }
};
