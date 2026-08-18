<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Catalog\Sede;
use App\Traits\ScopedBySede;

class CargaHistorial extends Model
{
    use ScopedBySede;

    protected $table = 'cargas_historial';

    protected $fillable = [
        'sede_id',
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
     * Sede a la que pertenece esta acta de entrega.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    /**
     * Relación con los productos cargados en el detalle del historial.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(CargaProductoHistorial::class, 'carga_historial_id');
    }
}
