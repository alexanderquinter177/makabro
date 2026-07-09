<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;

class Auditoria extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'auditorias';

    /**
     * Campos asignables masivamente.
     *
     * tipo_auditoria: sorpresa | programada | arqueo
     * estado:         pendiente | en_progreso | completada
     */
    protected $fillable = [
        'sede_id',
        'usuario_id',
        'fecha_auditoria',
        'tipo_auditoria',
        'estado',
        'hallazgos',
        'diferencia_encontrada',
        'acciones_tomadas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'fecha_auditoria'       => 'date',
        'diferencia_encontrada' => 'decimal:2',
        'deleted_at'            => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Sede auditada */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /** Usuario que realizó la auditoría */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Filtrar por sede */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where('sede_id', $sedeId);
    }

    /** Filtrar por tipo de auditoría */
    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo_auditoria', $tipo);
    }

    /** Solo auditorías pendientes */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /** Solo auditorías en progreso */
    public function scopeEnProgreso($query)
    {
        return $query->where('estado', 'en_progreso');
    }

    /** Solo auditorías completadas */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /** Filtrar por rango de fecha */
    public function scopeEntreFechas($query, string $desde, string $hasta)
    {
        return $query->whereBetween('fecha_auditoria', [$desde, $hasta]);
    }
}
