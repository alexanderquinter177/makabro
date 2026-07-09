<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kardex_movimientos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('producto_id')->constrained('productos');
            
            // Origen del movimiento
            $table->enum('tipo_movimiento', [
                'entrada_compra', 
                'salida_venta', 
                'ajuste_entrada', 
                'ajuste_salida',
                'merma_novedad'
            ]);
            
            // Cantidad que se movió (Siempre positiva, el tipo_movimiento define si suma o resta)
            $table->decimal('cantidad', 12, 4);
            
            // El saldo físico que quedó en la sede EXACTAMENTE después de este movimiento
            $table->decimal('saldo_despues', 12, 4);
            
            // RELACIÓN POLIMÓRFICA: ¿Qué documento generó esto? 
            // Crea dos columnas: documento_origen_type (ej: App\Models\Factura) y documento_origen_id (ej: 25)
            $table->string('documento_origen_type')->nullable();
            $table->unsignedBigInteger('documento_origen_id')->nullable();
            $table->index(['documento_origen_type', 'documento_origen_id'], 'kardex_mov_orig_idx');
            
            // Auditoría estricta
            $table->text('notas')->nullable();
            $table->foreignId('created_by')->constrained('users'); // Quién causó el movimiento
            
            // Solo created_at, porque un Kardex no se actualiza (updated_at)
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kardex_movimientos');
    }
};