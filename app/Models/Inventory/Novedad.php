<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;
use App\Models\Recipe\Plato;
use App\Models\Catalog\Producto;

class Novedad extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'novedades';

    /**
     * Campos asignables masivamente.
     *
     * tipo:           caída/quiebre | quemado | vencimiento | daño | devolución | pérdida/robo
     * area:           cocina | barra | administración
     * tipo_afectado:  plato | producto | mueble
     * estado_cobro:   si | no | pendiente
     */
    protected $fillable = [
        'sede_id',
        'usuario_id',
        'responsable_id',
        'responsable_nombre',
        'tipo',
        'area',
        'tipo_afectado',
        'plato_id',
        'producto_id',
        'cantidad',
        'valor_costo',
        'valor_cobro',
        'estado_cobro',
        'descripcion',
        'evidencia_imagen',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'cantidad'    => 'decimal:2',
        'valor_costo' => 'decimal:2',
        'valor_cobro' => 'decimal:2',
        'deleted_at'  => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Sede donde ocurrió la novedad */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /** Usuario que registró la novedad */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /** Empleado responsable (si aplica cobro) */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /** Plato afectado (si tipo_afectado = 'plato') */
    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }

    /** Producto afectado (si tipo_afectado = 'producto') */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Filtrar por sede */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where('sede_id', $sedeId);
    }

    /** Filtrar por tipo de novedad */
    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /** Filtrar por área */
    public function scopeDeArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    /** Solo novedades con cobro pendiente */
    public function scopeCobroPendiente($query)
    {
        return $query->where('estado_cobro', 'pendiente');
    }

    /** Solo novedades donde aplica cobro */
    public function scopeConCobro($query)
    {
        return $query->where('estado_cobro', 'si');
    }
}
