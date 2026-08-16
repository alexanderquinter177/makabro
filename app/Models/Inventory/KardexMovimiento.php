<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;
use App\Models\Auth\User;

class KardexMovimiento extends Model
{
    use SoftDeletes;

     protected $table = 'kardex_movimientos';

    const UPDATED_AT = null;

    protected $fillable = [
        'sede_id',
        'producto_id',
        'tipo_movimiento',
        'cantidad',
        'saldo_anterior',
        'saldo_despues',
        'costo_unitario',
        'costo_total',
        'documento_origen_type',
        'documento_origen_id',
        'notas',
        'created_by',
    ];

    protected $casts = [
        'cantidad' => 'decimal:4',
        'saldo_anterior' => 'decimal:4',
        'saldo_despues' => 'decimal:4',
        'costo_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación Polimórfica: Retorna el modelo exacto que originó el movimiento.
     * Puede retornar una instancia de Compra, Venta, Inventario, etc.
     */
    public function documentoOrigen(): MorphTo
    {
        return $this->morphTo('documento_origen');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where($this->getTable() . '.sede_id', $sedeId);
    }

    public function scopeDeProducto($query, int $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    public function scopeEntradas($query)
    {
        return $query->whereIn('tipo_movimiento', ['entrada', 'entrada_compra', 'ajuste_entrada']);
    }

    public function scopeSalidas($query)
    {
        return $query->whereIn('tipo_movimiento', ['salida', 'salida_venta', 'ajuste_salida', 'merma_novedad']);
    }

    public function scopeAjustes($query)
    {
        return $query->whereIn('tipo_movimiento', ['ajuste_entrada', 'ajuste_salida']);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        return $query->whereBetween('created_at', [$desde, $hasta]);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getTipoColorAttribute(): string
    {
        return match($this->tipo_movimiento) {
            'entrada', 'entrada_compra', 'ajuste_entrada' => 'success',
            'salida', 'salida_venta', 'ajuste_salida', 'merma_novedad' => 'danger',
            default => 'gray',
        };
    }

    public function getTipoIconoAttribute(): string
    {
        return match($this->tipo_movimiento) {
            'entrada', 'entrada_compra' => '⬆️',
            'salida', 'salida_venta', 'merma_novedad' => '⬇️',
            'ajuste_entrada', 'ajuste_salida' => '🔄',
            default => '📌',
        };
    }

    // -------------------------------------------------------------------------
    // Métodos
    // -------------------------------------------------------------------------

    /**
     * Registrar un movimiento de entrada
     */
    public static function registrarEntrada(
        int $sedeId,
        int $productoId,
        float $cantidad,
        float $saldoAnterior,
        float $saldoNuevo,
        $documentoOrigen,
        ?float $costoUnitario = null,
        ?string $notas = null
    ): self {
        return self::create([
            'sede_id' => $sedeId,
            'producto_id' => $productoId,
            'tipo_movimiento' => 'entrada_compra',
            'cantidad' => $cantidad,
            'saldo_anterior' => $saldoAnterior,
            'saldo_despues' => $saldoNuevo,
            'costo_unitario' => $costoUnitario,
            'costo_total' => $costoUnitario ? $cantidad * $costoUnitario : null,
            'documento_origen_type' => get_class($documentoOrigen),
            'documento_origen_id' => $documentoOrigen->id,
            'notas' => $notas,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Registrar un movimiento de salida
     */
    public static function registrarSalida(
        int $sedeId,
        int $productoId,
        float $cantidad,
        float $saldoAnterior,
        float $saldoNuevo,
        $documentoOrigen,
        ?float $costoUnitario = null,
        ?string $notas = null
    ): self {
        return self::create([
            'sede_id' => $sedeId,
            'producto_id' => $productoId,
            'tipo_movimiento' => 'salida_venta',
            'cantidad' => $cantidad,
            'saldo_anterior' => $saldoAnterior,
            'saldo_despues' => $saldoNuevo,
            'costo_unitario' => $costoUnitario,
            'costo_total' => $costoUnitario ? $cantidad * $costoUnitario : null,
            'documento_origen_type' => get_class($documentoOrigen),
            'documento_origen_id' => $documentoOrigen->id,
            'notas' => $notas,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Registrar un ajuste (por inventario físico)
     */
    public static function registrarAjuste(
        int $sedeId,
        int $productoId,
        float $cantidad,
        float $saldoAnterior,
        float $saldoNuevo,
        $documentoOrigen,
        ?float $costoUnitario = null,
        ?string $notas = null
    ): self {
        return self::create([
            'sede_id' => $sedeId,
            'producto_id' => $productoId,
            'tipo_movimiento' => $cantidad >= 0 ? 'ajuste_entrada' : 'ajuste_salida',
            'cantidad' => abs($cantidad),
            'saldo_anterior' => $saldoAnterior,
            'saldo_despues' => $saldoNuevo,
            'costo_unitario' => $costoUnitario,
            'costo_total' => $costoUnitario ? abs($cantidad) * $costoUnitario : null,
            'documento_origen_type' => get_class($documentoOrigen),
            'documento_origen_id' => $documentoOrigen->id,
            'notas' => $notas,
            'created_by' => auth()->id(),
        ]);
    }
}