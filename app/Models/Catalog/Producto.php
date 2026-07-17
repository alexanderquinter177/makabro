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
        'tipo',      
        'precio_compra',
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


    public function unidadCompra(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_compra_id');
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

    public static function generarCodigo(string $tipo, int $categoriaId): string
    {
        $prefijoTipo = self::getPrefijoTipo($tipo);
        
        $categoria = Categoria::find($categoriaId);
        $codigoCategoria = $categoria ? strtoupper(substr($categoria->nombre, 0, 3)) : 'GEN';
        
        $ultimo = self::where('tipo', $tipo)
                      ->where('categoria_id', $categoriaId)
                      ->orderBy('id', 'desc')
                      ->first();
        
        if ($ultimo && preg_match('/-(\d{3})$/', $ultimo->codigo, $matches)) {
            $numero = intval($matches[1]) + 1;
        } else {
            $numero = 1;
        }
        
        $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);
        
        return "{$prefijoTipo}-{$codigoCategoria}-{$numeroFormateado}";
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
            return $this->precio_compra ?? 0;
        }

        // Si tiene ingredientes, sumar costos
        if ($this->tieneIngredientes()) {
            $costo = 0;
            foreach ($this->ingredientes as $ingrediente) {
                $costo += $ingrediente->calcularCosto() * $ingrediente->pivot->cantidad;
            }
            return $costo;
        }

        return $this->precio_compra ?? 0;
    }

    public function getCostoUnitario(): float
    {
        if ($this->tipo === 'insumo') {
            return $this->precio_compra ?? 0;
        }
        return $this->calcularCosto();
    }
}