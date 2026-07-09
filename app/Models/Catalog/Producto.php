<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;

class Producto extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'codigo',
        'nombre',
        'tipo', // 'venta', 'subensamble', 'insumo'
        'unidad_medida_id',
        'precio_unitario',
        'proveedor_habitual',
        'activo',
        'notas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'activo' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    // -------------------------------------------------------------------------
    // Relaciones BOM (Recetas)
    // -------------------------------------------------------------------------

    public function ingredientes(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'recetas_bom',
            'producto_padre_id',
            'producto_hijo_id'
        )->withPivot('cantidad', 'nota')
         ->withTimestamps();
    }

    public function usadoEn(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'recetas_bom',
            'producto_hijo_id',
            'producto_padre_id'
        )->withPivot('cantidad', 'nota')
         ->withTimestamps();
    }

    public function componentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'recetas_bom',
            'producto_padre_id',
            'producto_hijo_id'
        )->withPivot('cantidad', 'nota')
         ->withTimestamps();
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    // -------------------------------------------------------------------------
    // Métodos para generar código automático
    // -------------------------------------------------------------------------

    /**
     * Generar código automático según tipo y categoría
     */
    public static function generarCodigo(string $tipo, int $categoriaId): string
    {
        // 1. Obtener el prefijo según el tipo
        $prefijoTipo = self::getPrefijoTipo($tipo);
        
        // 2. Obtener el código de la categoría
        $categoria = Categoria::find($categoriaId);
        $codigoCategoria = $categoria ? strtoupper(substr($categoria->nombre, 0, 3)) : 'GEN';
        
        // 3. Obtener el último número consecutivo
        $ultimo = self::where('tipo', $tipo)
                      ->where('categoria_id', $categoriaId)
                      ->orderBy('id', 'desc')
                      ->first();
        
        if ($ultimo && preg_match('/-(\d{3})$/', $ultimo->codigo, $matches)) {
            $numero = intval($matches[1]) + 1;
        } else {
            $numero = 1;
        }
        
        // 4. Formatear el número a 3 dígitos
        $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);
        
        // 5. Generar el código completo
        return "{$prefijoTipo}-{$codigoCategoria}-{$numeroFormateado}";
    }

    /**
     * Obtener el prefijo según el tipo de producto
     */
    private static function getPrefijoTipo(string $tipo): string
    {
        return match($tipo) {
            'venta' => 'VE',
            'subensamble' => 'SU',
            'insumo' => 'IN',
            default => 'PR',
        };
    }

    /**
     * Validar que el código tenga el formato correcto
     */
    public static function validarCodigo(string $codigo): bool
    {
        return preg_match('/^(VE|SU|IN)-[A-Z]{3}-\d{3}$/', $codigo) === 1;
    }

    // -------------------------------------------------------------------------
    // Boot del modelo
    // -------------------------------------------------------------------------

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($producto) {
            // Si no se ha asignado un código, generarlo automáticamente
            if (empty($producto->codigo)) {
                $producto->codigo = self::generarCodigo(
                    $producto->tipo,
                    $producto->categoria_id
                );
            }
        });
    }

    // -------------------------------------------------------------------------
    // Métodos existentes
    // -------------------------------------------------------------------------

    public function esPlato(): bool
    {
        return $this->tipo === 'venta';
    }

    public function esSubreceta(): bool
    {
        return $this->tipo === 'subensamble';
    }

    public function esInsumo(): bool
    {
        return $this->tipo === 'insumo';
    }

    public function tieneIngredientes(): bool
    {
        return $this->ingredientes()->exists();
    }

    public function obtenerTodosLosIngredientes(): array
    {
        $ingredientes = [];

        foreach ($this->ingredientes as $ingrediente) {
            if ($ingrediente->esSubreceta()) {
                $subIngredientes = $ingrediente->obtenerTodosLosIngredientes();
                foreach ($subIngredientes as $sub) {
                    $ingredientes[] = [
                        'producto' => $sub['producto'],
                        'cantidad' => $sub['cantidad'] * $ingrediente->pivot->cantidad,
                        'nota' => $sub['nota'] ?? null,
                    ];
                }
            } else {
                $ingredientes[] = [
                    'producto' => $ingrediente,
                    'cantidad' => $ingrediente->pivot->cantidad,
                    'nota' => $ingrediente->pivot->nota,
                ];
            }
        }

        return $ingredientes;
    }

    public function calcularCosto(): float
    {
        $costo = 0;

        foreach ($this->ingredientes as $ingrediente) {
            $costo += $ingrediente->precio_unitario * $ingrediente->pivot->cantidad;
        }

        return $costo;
    }
}