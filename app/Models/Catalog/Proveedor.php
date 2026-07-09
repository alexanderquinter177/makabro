<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Purchase\Compra;

class Proveedor extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'proveedores';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'nit',
        'telefono',
        'email',
        'direccion',
        'persona_contacto',
        'activo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'activo'     => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Compras realizadas a este proveedor */
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Solo proveedores activos */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
