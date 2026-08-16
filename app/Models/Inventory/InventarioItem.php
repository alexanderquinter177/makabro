<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Producto;

class InventarioItem extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'inventario_items';

    protected $fillable = [
        'inventario_id',
        'producto_id',
        'cantidad_contada',
        'cantidad_sistema',
        'diferencia',
        'costo_unitario',
        'valor_total',
        'observacion',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'cantidad_contada' => 'decimal:2',
        'cantidad_sistema' => 'decimal:2',
        'diferencia' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class)->withoutGlobalScope('sede');
    }

    // -------------------------------------------------------------------------
    // Métodos
    // -------------------------------------------------------------------------

    public function calcularDiferencia()
    {
        $this->diferencia = $this->cantidad_contada - ($this->cantidad_sistema ?? 0);
        $this->save();
        return $this->diferencia;
    }

    public function calcularValorTotal()
    {
        $this->valor_total = $this->cantidad_contada * $this->costo_unitario;
        $this->save();
        return $this->valor_total;
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeDeInventario($query, int $inventarioId)
    {
        return $query->where('inventario_id', $inventarioId);
    }

    public function scopeConDiferencia($query)
    {
        return $query->where('diferencia', '!=', 0);
    }

    public function scopeDeProducto($query, int $productoId)
    {
        return $query->where('producto_id', $productoId);
    }
}