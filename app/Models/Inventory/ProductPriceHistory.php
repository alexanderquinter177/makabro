<?php

namespace App\Models\Inventory;

use App\Models\Catalog\Producto;
use App\Models\Catalog\Proveedor;
use App\Models\Purchase\Compra;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceHistory extends Model
{
    protected $table = 'product_price_histories';

    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'compra_id',
        'precio_anterior',
        'precio_nuevo',
        'unidad_base',
    ];

    protected $casts = [
        'precio_anterior' => 'decimal:4',
        'precio_nuevo'    => 'decimal:4',
    ];

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    // ─── Accessor: variación porcentual ────────────────────────────────────────

    public function getVariacionPorcentualAttribute(): ?float
    {
        if (!$this->precio_anterior || $this->precio_anterior == 0) {
            return null;
        }

        return round((($this->precio_nuevo - $this->precio_anterior) / $this->precio_anterior) * 100, 2);
    }
}
