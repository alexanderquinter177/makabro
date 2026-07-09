<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Sede;
use App\Models\Purchase\Compra;
use App\Models\Inventory\Inventario;
use App\Models\Inventory\Novedad;
use App\Models\Audit\Auditoria;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasAuditSignature,HasRoles;

    protected $table = 'users';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'cedula',          // Nuevo campo
        'password',
        'cargo',
        'telefono',
        'activo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Atributos ocultos en la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversiones de tipos de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'activo'            => 'boolean',
            'deleted_at'        => 'datetime',
        ];
    }

    /**
     * El identificador de autenticación siempre es la clave primaria (id).
     * Esto garantiza que Auth::id() retorne el ID numérico del usuario,
     * no la cédula. La autenticación por cédula se maneja en CustomLogin.
     */
    public function getAuthIdentifierName(): string
    {
        return $this->getKeyName(); // Siempre retorna 'id'
    }

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /**
     * Sedes a las que pertenece el usuario (relación muchos a muchos)
     */
    public function sedes(): BelongsToMany
    {
        return $this->belongsToMany(Sede::class, 'sede_user', 'user_id', 'sede_id')
                    ->withTimestamps()
                    ->withPivot('activo', 'cargo_sede');
    }

    /**
     * Sedes activas del usuario
     */
    public function sedesActivas()
    {
        return $this->sedes()->wherePivot('activo', true);
    }

    /**
     * Obtener la sede actual (de sesión)
     */
    public function getSedeActualAttribute()
    {
        $sedeId = session('sede_id');
        if ($sedeId) {
            return $this->sedes()->where('sede_id', $sedeId)->first();
        }
        return $this->sedes()->first();
    }

    /**
     * Verificar si el usuario tiene acceso a una sede específica
     */
    public function tieneAccesoASede(int $sedeId): bool
    {
        return $this->sedes()->where('sede_id', $sedeId)->exists();
    }

    /**
     * Obtener el ID de la sede actual (de sesión)
     */
    public function getSedeIdActualAttribute()
    {
        return session('sede_id') ?? $this->sedes()->first()?->id;
    }

    /** Compras que ha registrado este usuario */
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }

    /** Inventarios que ha realizado este usuario */
    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'usuario_id');
    }

    /** Novedades registradas por este usuario */
    public function novedadesRegistradas(): HasMany
    {
        return $this->hasMany(Novedad::class, 'usuario_id');
    }

    /** Novedades en las que este usuario es responsable */
    public function novedadesComoResponsable(): HasMany
    {
        return $this->hasMany(Novedad::class, 'responsable_id');
    }

    /** Auditorías realizadas por este usuario */
    public function auditorias(): HasMany
    {
        return $this->hasMany(Auditoria::class, 'usuario_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Solo usuarios activos */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /** Buscar por cédula */
    public function scopePorCedula($query, string $cedula)
    {
        return $query->where('cedula', $cedula);
    }

    /** Filtrar usuarios por sede */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->whereHas('sedes', function ($q) use ($sedeId) {
            $q->where('sede_id', $sedeId);
        });
    }

    /** Filtrar usuarios que tengan al menos una sede activa */
    public function scopeConSedesActivas($query)
    {
        return $query->whereHas('sedes', function ($q) {
            $q->wherePivot('activo', true);
        });
    }
}