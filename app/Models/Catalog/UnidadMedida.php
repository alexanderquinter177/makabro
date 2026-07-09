<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;

class UnidadMedida extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'unidades_medida';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'abreviatura',
        'activo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'activo' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Productos que utilizan esta unidad de medida */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'unidad_medida_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Solo unidades activas
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Buscar por nombre o abreviatura
     */
    public function scopeBuscar($query, string $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('abreviatura', 'like', "%{$termino}%");
        });
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Obtener el nombre completo con la abreviatura
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' (' . $this->abreviatura . ')';
    }
}