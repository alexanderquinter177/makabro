<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargaProductoHistorial extends Model
{
    protected $table = 'cargas_productos_historial';

    protected $fillable = [
        'carga_historial_id',
        'codigo',
        'nombre_producto',
        'categoria',
        'tipo_producto',
        'unidad_medida',
        'cantidad',
        'precio',
        'total_linea',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio' => 'decimal:2',
        'total_linea' => 'decimal:2',
    ];

    /**
     * Relación pertenencia con el maestro de la carga en el historial.
     */
    public function cargaHistorial(): BelongsTo
    {
        return $this->belongsTo(CargaHistorial::class, 'carga_historial_id');
    }
}
