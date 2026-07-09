<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Auth\User;
use App\Models\Purchase\Compra;
use App\Models\Recipe\Plato;
use App\Models\Inventory\Inventario;
use App\Models\Inventory\Novedad;
use App\Models\Audit\Auditoria;

class Sede extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'sedes';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'marca',
        'direccion',
        'telefono',
        'email',
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

    /**
     * Usuarios asignados a esta sede (relación muchos a muchos)
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sede_user', 'sede_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot('activo', 'cargo_sede');
    }

    /**
     * Usuarios activos de esta sede
     */
    public function usuariosActivos()
    {
        return $this->usuarios()->wherePivot('activo', true);
    }

    /** Compras realizadas en esta sede */
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    /** Platos disponibles en esta sede */
    public function platos(): HasMany
    {
        return $this->hasMany(Plato::class);
    }

    /** Inventarios registrados en esta sede */
    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class);
    }

    /** Novedades registradas en esta sede */
    public function novedades(): HasMany
    {
        return $this->hasMany(Novedad::class);
    }

    /** Auditorías realizadas en esta sede */
    public function auditorias(): HasMany
    {
        return $this->hasMany(Auditoria::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Solo sedes activas */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /** Filtrar por marca (Makabro, Carmela, etc.) */
    public function scopeDeMarca($query, string $marca)
    {
        return $query->where('marca', $marca);
    }
}