<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Producto;

class CompraItem extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'compra_items';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'total',
        'notas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'total'           => 'decimal:2',
        'deleted_at'      => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Compra a la que pertenece este ítem */
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    /** Producto comprado */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // -------------------------------------------------------------------------
    // Métodos
    // -------------------------------------------------------------------------

    /**
     * Calcular el total del ítem (cantidad * precio_unitario)
     */
    public function calcularTotal(): float
    {
        $this->total = $this->cantidad * $this->precio_unitario;
        $this->save();
        return $this->total;
    }

    /**
     * Actualizar el total de la compra padre
     */
    public function actualizarTotalCompra(): void
    {
        $compra = $this->compra;
        if ($compra) {
            $compra->calcularTotales();
        }
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Filtrar items por compra
     */
    public function scopeDeCompra($query, int $compraId)
    {
        return $query->where('compra_id', $compraId);
    }

    /**
     * Filtrar items por producto
     */
    public function scopeDeProducto($query, int $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    /**
     * Filtrar items con total mayor a un valor
     */
    public function scopeTotalMayorA($query, float $valor)
    {
        return $query->where('total', '>', $valor);
    }
}