<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargaHistorial extends Model
{
    protected $table = 'cargas_historial';

    protected $fillable = [
        'fecha',
        'cargo_recibe',
        'nombre_recibe',
        'tipo',
        'valor_total',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($carga) {
            if (!empty($carga->cargo_recibe)) {
                $carga->cargo_recibe = mb_strtoupper(trim($carga->cargo_recibe), 'UTF-8');
            }
            if (!empty($carga->nombre_recibe)) {
                $carga->nombre_recibe = mb_strtoupper(trim($carga->nombre_recibe), 'UTF-8');
            }
        });
    }

    /**
     * Relación con los productos cargados en el detalle del historial.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(CargaProductoHistorial::class, 'carga_historial_id');
    }
}
