<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Traits\ScopedBySede;

class Producto extends Model
{
    use SoftDeletes, HasAuditSignature, ScopedBySede;

    protected $table = 'productos';

    protected $fillable = [
        'sede_id',
        'categoria_id',
        'codigo',
        'nombre',
        'tipo',      
        'precio_compra',
        'costo_venta',
        'proveedor_habitual',
        'activo',
        'notas',
        'created_by',
        'updated_by',
        'deleted_by',
        'unidad_compra_id',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'costo_venta' => 'decimal:2',
        'activo' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidadCompra(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_compra_id');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_compra_id');
    }

    public function historialPrecios(): HasMany
    {
        return $this->hasMany(\App\Models\Inventory\ProductPriceHistory::class, 'producto_id')
                    ->latest();
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
        )->withoutGlobalScope('sede')
         ->withPivot('cantidad', 'nota')
         ->withTimestamps();
    }

    public function usadoEn(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'recetas_bom',
            'producto_hijo_id',
            'producto_padre_id'
        )->withoutGlobalScope('sede')
         ->withPivot('cantidad', 'nota')
         ->withTimestamps();
    }

    public function componentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'recetas_bom',
            'producto_padre_id',
            'producto_hijo_id'
        )->withoutGlobalScope('sede')
         ->withPivot('cantidad', 'nota')
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

    public static function generarCodigo(?string $tipo = null, ?int $categoriaId = null): string
    {
        $tipoActual = $tipo ?: 'insumo';
        $prefijoTipo = self::getPrefijoTipo($tipoActual);
        
        $codigoCategoria = 'GEN';
        if ($categoriaId) {
            $categoria = Categoria::find($categoriaId);
            if ($categoria && !empty($categoria->nombre)) {
                $codigoCategoria = strtoupper(substr(trim($categoria->nombre), 0, 3));
            }
        }
        
        $prefix = "{$prefijoTipo}-{$codigoCategoria}-";

        // Obtener todos los códigos que inician con el prefijo, incluyendo soft-deleted
        $codigos = self::withTrashed()
            ->where('codigo', 'LIKE', "{$prefix}%")
            ->pluck('codigo');

        $maxNumero = 0;
        foreach ($codigos as $cod) {
            if (preg_match('/-(\d{3,})$/', $cod, $matches)) {
                $num = intval($matches[1]);
                if ($num > $maxNumero) {
                    $maxNumero = $num;
                }
            }
        }

        $numero = $maxNumero + 1;

        // Asegurar que el código candidato no exista en la base de datos (incluso soft-deleted)
        do {
            $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);
            $candidate = "{$prefix}{$numeroFormateado}";
            $exists = self::withTrashed()->where('codigo', $candidate)->exists();
            if ($exists) {
                $numero++;
            }
        } while ($exists);

        return $candidate;
    }

    private static function getPrefijoTipo(string $tipo): string
    {
        return match($tipo) {
            'venta' => 'VE',
            'subensamble' => 'SU',
            'insumo' => 'IN',
            default => 'PR',
        };
    }

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
            if (empty($producto->tipo)) {
                $producto->tipo = 'insumo';
            }
            if (empty($producto->categoria_id)) {
                $primeraCat = Categoria::where('activo', true)->first();
                if ($primeraCat) {
                    $producto->categoria_id = $primeraCat->id;
                }
            }
            if (empty($producto->codigo)) {
                $producto->codigo = self::generarCodigo(
                    $producto->tipo,
                    $producto->categoria_id
                );
            }
        });

        static::saving(function ($producto) {
            if ($producto->unidad_compra_id) {
                $unidad = \App\Models\Catalog\UnidadMedida::find($producto->unidad_compra_id);
                if ($unidad) {
                    $abv = strtolower($unidad->abreviatura);
                    if ($abv === 'kg') {
                        $unidadGr = \App\Models\Catalog\UnidadMedida::where('abreviatura', 'gr')->first();
                        if ($unidadGr) {
                            $producto->unidad_compra_id = $unidadGr->id;
                            $producto->precio_compra = ($producto->precio_compra ?? 0) / 1000;
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Conversión de Kilogramos a Gramos')
                                ->body("El producto '{$producto->nombre}' se convirtió automáticamente a Gramos (gr) y el precio se dividió por 1000.")
                                ->success()
                                ->send();
                        }
                    } elseif ($abv === 'lt') {
                        $unidadMl = \App\Models\Catalog\UnidadMedida::where('abreviatura', 'ml')->first();
                        if ($unidadMl) {
                            $producto->unidad_compra_id = $unidadMl->id;
                            $producto->precio_compra = ($producto->precio_compra ?? 0) / 1000;
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Conversión de Litros a Mililitros')
                                ->body("El producto '{$producto->nombre}' se convirtió automáticamente a Mililitros (ml) y el precio se dividió por 1000.")
                                ->success()
                                ->send();
                        }
                    } elseif ($abv === 'gr' || $abv === 'ml') {
                        // Ya está en unidad base de uso, no requiere conversión
                    } else {
                        // Unidad no convertible como 'und' (Unidades)
                        \Filament\Notifications\Notification::make()
                            ->title('Unidad no convertible')
                            ->body("La unidad '{$unidad->nombre}' es por unidad, no es convertible a gramos.")
                            ->info()
                            ->send();
                    }
                }
            }
        });


    }

    // -------------------------------------------------------------------------
    // Métodos
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
        // Si es insumo, usar precio_compra
        if ($this->tipo === 'insumo') {
            return floatval($this->precio_compra ?? 0);
        }

        // Si tiene ingredientes, calcular costos
        if ($this->tieneIngredientes()) {
            $costoTotalBatch = 0;
            $rendimientoBatch = 0;

            foreach ($this->ingredientes as $ingrediente) {
                $cantidad = floatval($ingrediente->pivot->cantidad ?? 0);
                $costoTotalBatch += $ingrediente->getCostoUnitario() * $cantidad;
                $rendimientoBatch += $cantidad;
            }

            // Si es una subreceta (subensamble), el costo unitario por gramo/unidad es (costo total batch / rendimiento)
            if ($this->tipo === 'subensamble') {
                return $rendimientoBatch > 0 ? ($costoTotalBatch / $rendimientoBatch) : $costoTotalBatch;
            }

            // Si es producto de venta, el costo total del plato es la suma de sus insumos/subrecetas
            return $costoTotalBatch;
        }

        return floatval($this->precio_compra ?? 0);
    }

    public function getCostoUnitario(): float
    {
        if ($this->tipo === 'insumo') {
            return floatval($this->precio_compra ?? 0);
        }

        if ($this->tipo === 'subensamble' && floatval($this->precio_compra) > 0) {
            return floatval($this->precio_compra);
        }

        return $this->calcularCosto();
    }
}