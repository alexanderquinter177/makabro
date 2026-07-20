<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Catalog\Producto;

class SalesReportImportItem extends Model
{
    protected $table = 'sales_report_import_items';

    protected $fillable = [
        'import_id',
        'product_id',
        'punto_operacion',
        'grupo',
        'producto_nombre',
        'unidad',
        'cortesia',
        'hora_feliz',
        'consumo',
        'baja_dano',
        'cantidad_venta',
        'venta_bruta',
        'descuento',
        'venta_neta',
        'impuesto',
        'total',
        'v_unitario',
        'porcentaje',
    ];

    protected $casts = [
        'cortesia'       => 'decimal:4',
        'hora_feliz'     => 'decimal:4',
        'consumo'        => 'decimal:4',
        'baja_dano'      => 'decimal:4',
        'cantidad_venta' => 'decimal:4',
        'venta_bruta'    => 'decimal:4',
        'descuento'      => 'decimal:4',
        'venta_neta'     => 'decimal:4',
        'impuesto'       => 'decimal:4',
        'total'          => 'decimal:4',
        'v_unitario'     => 'decimal:4',
        'porcentaje'     => 'decimal:4',
    ];

    /**
     * Reporte principal de importación.
     */
    public function import(): BelongsTo
    {
        return $this->belongsTo(SalesReportImport::class, 'import_id');
    }

    /**
     * Producto cruzado (si fue encontrado en la BD).
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }
}
