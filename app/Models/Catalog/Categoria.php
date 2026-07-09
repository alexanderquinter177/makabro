<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;

class Categoria extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'categorias';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'slug',
        'color',
        'descripcion',
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

    /**
     * Productos que pertenecen a esta categoría
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Solo categorías activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Obtener el color con formato hexadecimal
     */
    public function getColorFormateadoAttribute(): string
    {
        return $this->color ?: '#6B7280'; // Color gris por defecto
    }
}