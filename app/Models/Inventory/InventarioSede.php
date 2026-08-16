<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;

class InventarioSede extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'inventario_sedes';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'sede_id',
        'producto_id',
        'cantidad_actual',
        'stock_minimo',
        'stock_maximo',
        'punto_reorden',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'cantidad_actual' => 'decimal:2',
        'stock_minimo'    => 'decimal:2',
        'stock_maximo'    => 'decimal:2',
        'punto_reorden'   => 'decimal:2',
        'deleted_at'      => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Sede a la que pertenece este stock */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /** Producto/Insumo controlado */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class)->withoutGlobalScope('sede');
    }

    // -------------------------------------------------------------------------
    // Scopes (Filtros listos para usar en Filament)
    // -------------------------------------------------------------------------

    /** Filtrar por una sede específica */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where($this->getTable() . '.sede_id', $sedeId);
    }

    /** * Alerta Roja: Productos cuyo stock actual es menor o igual al mínimo.
     * Ideal para un badge en Filament o para enviar correos de alerta.
     */
    public function scopeBajoStockMinimo($query)
    {
        return $query->whereColumn('cantidad_actual', '<=', 'stock_minimo');
    }

    /** * Sugerencia de compra: Productos que ya tocaron su punto de reorden
     */
    public function scopeEnPuntoDeReorden($query)
    {
        return $query->whereColumn('cantidad_actual', '<=', 'punto_reorden');
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Calcula cuánto falta para llenar el stock al máximo.
     * Útil para autocompletar órdenes de compra.
     */
    public function getSugeridoCompraAttribute(): float
    {
        $diferencia = (float) $this->stock_maximo - (float) $this->cantidad_actual;
        return $diferencia > 0 ? $diferencia : 0;
    }
}