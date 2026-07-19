<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Producto;

class CompraItem extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'compra_items';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'compra_id',
        'producto_id',
        'unidad_compra',
        'cantidad',
        'precio_unitario',
        'total',
        'notas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'total'           => 'decimal:2',
        'deleted_at'      => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Compra a la que pertenece este ítem */
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    /** Producto comprado */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    protected static function booted()
    {
        // 1. Al crear un ítem (comprar producto)
        static::created(function ($item) {
            $compra = $item->compra;
            if (!$compra || $compra->status !== 'aprobado') return;

            $item->procesarIngresoStock();
        });

        // 2. Al actualizar un ítem (modificar cantidad/precio)
        static::updated(function ($item) {
            $compra = $item->compra;
            if (!$compra || $compra->status !== 'aprobado') return;

            $producto = $item->producto;
            $unidadCompraSeleccionada = strtolower($item->unidad_compra ?? 'gr');
            $unidadBaseCatalogo = $producto && $producto->unidadCompra ? strtolower($producto->unidadCompra->abreviatura) : 'gr';
            
            $cantAntRaw = floatval($item->getOriginal('cantidad'));
            $cantNueRaw = floatval($item->cantidad);
            
            $factor = 1;
            if ($unidadBaseCatalogo === 'gr' && $unidadCompraSeleccionada === 'kg') {
                $factor = 1000;
            } elseif ($unidadBaseCatalogo === 'ml' && $unidadCompraSeleccionada === 'lt') {
                $factor = 1000;
            }
            
            $diffCantidad = ($cantNueRaw - $cantAntRaw) * $factor;
            $precioEnBase = floatval($item->precio_unitario) / $factor;
            if ($diffCantidad != 0) {
                $stock = \App\Models\Inventory\InventarioSede::withTrashed()->firstOrCreate([
                    'sede_id' => $compra->sede_id,
                    'producto_id' => $item->producto_id,
                ]);
                
                if ($stock->trashed()) {
                    $stock->restore();
                }
                
                $saldoAnterior = $stock->cantidad_actual;
                $saldoNuevo = max(0, $saldoAnterior + $diffCantidad);
                
                $stock->cantidad_actual = $saldoNuevo;
                $stock->updated_by = $compra->usuario_id;
                $stock->save();

                \App\Models\Inventory\KardexMovimiento::create([
                    'sede_id' => $compra->sede_id,
                    'producto_id' => $item->producto_id,
                    'tipo_movimiento' => $diffCantidad > 0 ? 'ajuste_entrada' : 'ajuste_salida',
                    'cantidad' => abs($diffCantidad),
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_despues' => $saldoNuevo,
                    'costo_unitario' => $precioEnBase,
                    'costo_total' => abs($diffCantidad * $precioEnBase),
                    'documento_origen_type' => Compra::class,
                    'documento_origen_id' => $compra->id,
                    'notas' => 'Actualización ítem compra: ' . $compra->numero_factura,
                    'created_by' => $compra->usuario_id,
                ]);
            }

            if ($producto) {
                $producto->precio_compra = $precioEnBase;
                $producto->save();
            }
        });

        // 3. Al eliminar un ítem de la compra
        static::deleted(function ($item) {
            $compra = $item->compra;
            if (!$compra || $compra->status !== 'aprobado') return;

            $producto = $item->producto;
            $unidadCompraSeleccionada = strtolower($item->unidad_compra ?? 'gr');
            $unidadBaseCatalogo = $producto && $producto->unidadCompra ? strtolower($producto->unidadCompra->abreviatura) : 'gr';
            
            $cantidadEnBase = floatval($item->cantidad);
            $precioEnBase = floatval($item->precio_unitario);
            
            if ($unidadBaseCatalogo === 'gr' && $unidadCompraSeleccionada === 'kg') {
                $cantidadEnBase = $cantidadEnBase * 1000;
                $precioEnBase = $precioEnBase / 1000;
            } elseif ($unidadBaseCatalogo === 'ml' && $unidadCompraSeleccionada === 'lt') {
                $cantidadEnBase = $cantidadEnBase * 1000;
                $precioEnBase = $precioEnBase / 1000;
            }

            $stock = \App\Models\Inventory\InventarioSede::withTrashed()
                ->where([
                    'sede_id' => $compra->sede_id,
                    'producto_id' => $item->producto_id,
                ])->first();
            
            if ($stock) {
                $saldoAnterior = $stock->cantidad_actual;
                $saldoNuevo = max(0, $saldoAnterior - $cantidadEnBase);
                
                $stock->cantidad_actual = $saldoNuevo;
                $stock->updated_by = $compra->usuario_id;
                $stock->save();

                \App\Models\Inventory\KardexMovimiento::create([
                    'sede_id' => $compra->sede_id,
                    'producto_id' => $item->producto_id,
                    'tipo_movimiento' => 'ajuste_salida',
                    'cantidad' => $cantidadEnBase,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_despues' => $saldoNuevo,
                    'costo_unitario' => $precioEnBase,
                    'costo_total' => $item->total,
                    'documento_origen_type' => Compra::class,
                    'documento_origen_id' => $compra->id,
                    'notas' => 'Eliminación ítem compra: ' . $compra->numero_factura,
                    'created_by' => $compra->usuario_id,
                ]);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Métodos
    // -------------------------------------------------------------------------

    /**
     * Procesar el ingreso al inventario y registrar en Kardex para este ítem.
     */
    public function procesarIngresoStock(): void
    {
        $compra = $this->compra;
        if (!$compra) return;

        $producto = $this->producto;
        $unidadCompraSeleccionada = strtolower($this->unidad_compra ?? 'gr');
        $unidadBaseCatalogo = $producto && $producto->unidadCompra ? strtolower($producto->unidadCompra->abreviatura) : 'gr';
        
        $cantidadEnBase = floatval($this->cantidad);
        $precioEnBase = floatval($this->precio_unitario);
        
        if ($unidadBaseCatalogo === 'gr' && $unidadCompraSeleccionada === 'kg') {
            $cantidadEnBase = $cantidadEnBase * 1000;
            $precioEnBase = $precioEnBase / 1000;
        } elseif ($unidadBaseCatalogo === 'ml' && $unidadCompraSeleccionada === 'lt') {
            $cantidadEnBase = $cantidadEnBase * 1000;
            $precioEnBase = $precioEnBase / 1000;
        }

        $stock = \App\Models\Inventory\InventarioSede::withTrashed()->firstOrCreate([
            'sede_id' => $compra->sede_id,
            'producto_id' => $this->producto_id,
        ]);
        
        if ($stock->trashed()) {
            $stock->restore();
        }
        
        $saldoAnterior = $stock->cantidad_actual;
        $saldoNuevo = $saldoAnterior + $cantidadEnBase;
        
        $stock->cantidad_actual = $saldoNuevo;
        $stock->updated_by = $compra->usuario_id;
        $stock->save();
        
        // Registrar movimiento en Kardex
        \App\Models\Inventory\KardexMovimiento::create([
            'sede_id' => $compra->sede_id,
            'producto_id' => $this->producto_id,
            'tipo_movimiento' => 'entrada_compra',
            'cantidad' => $cantidadEnBase,
            'saldo_anterior' => $saldoAnterior,
            'saldo_despues' => $saldoNuevo,
            'costo_unitario' => $precioEnBase,
            'costo_total' => $this->total,
            'documento_origen_type' => Compra::class,
            'documento_origen_id' => $compra->id,
            'notas' => 'Compra: ' . $compra->numero_factura,
            'created_by' => $compra->usuario_id,
        ]);

        // Actualizar catálogo de productos
        if ($producto) {
            $producto->precio_compra = $precioEnBase;
            $producto->save();
        }
    }

    /**
     * Calcular el total del ítem (cantidad * precio_unitario)
     */
    public function calcularTotal(): float
    {
        $this->total = $this->cantidad * $this->precio_unitario;
        $this->save();
        return $this->total;
    }

    /**
     * Actualizar el total de la compra padre
     */
    public function actualizarTotalCompra(): void
    {
        $compra = $this->compra;
        if ($compra) {
            $compra->calcularTotales();
        }
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Filtrar items por compra
     */
    public function scopeDeCompra($query, int $compraId)
    {
        return $query->where('compra_id', $compraId);
    }

    /**
     * Filtrar items por producto
     */
    public function scopeDeProducto($query, int $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    /**
     * Filtrar items con total mayor a un valor
     */
    public function scopeTotalMayorA($query, float $valor)
    {
        return $query->where('total', '>', $valor);
    }
}