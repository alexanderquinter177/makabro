<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Proveedor;
use App\Models\Auth\User;
use App\Models\Inventory\KardexMovimiento;
use App\Models\Inventory\InventarioSede;

class Compra extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'compras';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'sede_id',
        'proveedor_id',
        'usuario_id',
        'numero_factura',
        'fecha_factura',
        'fecha_registro',
        'forma_pago',
        'tipo_compra',
        'recibido_por',
        'subtotal',
        'iva',
        'total',
        'imagen_factura',
        'notas',
        'registro_tardio',
        'recibido',
        'pagado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'fecha_factura'   => 'date',
        'fecha_registro'  => 'date',
        'subtotal'        => 'decimal:2',
        'iva'             => 'decimal:2',
        'total'           => 'decimal:2',
        'registro_tardio' => 'boolean',
        'recibido'        => 'boolean',
        'pagado'          => 'boolean',
        'deleted_at'      => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Sede donde se realizó la compra */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /** Proveedor de la compra */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    /** Usuario que registró la compra */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /** Ítems/productos de la compra */
    public function items(): HasMany
    {
        return $this->hasMany(CompraItem::class);
    }

    /** Movimientos de Kardex generados por esta compra */
    public function kardexMovimientos(): MorphMany
    {
        return $this->morphMany(KardexMovimiento::class, 'documento_origen');
    }

    // -------------------------------------------------------------------------
    // Métodos
    // -------------------------------------------------------------------------

    /**
     * Calcular subtotal, IVA y total de la compra
     */
    public function calcularTotales(): array
    {
        // Calcular subtotal (suma de todos los items)
        $this->subtotal = $this->items->sum('total');
        
        // Calcular IVA (19%)
        $this->iva = $this->subtotal * 0.19;
        
        // Calcular total
        $this->total = $this->subtotal + $this->iva;
        
        $this->save();
        
        return [
            'subtotal' => $this->subtotal,
            'iva' => $this->iva,
            'total' => $this->total,
        ];
    }

    /**
     * Generar código de factura automático
     */
    public static function generarNumeroFactura($sedeId): string
    {
        $sede = Sede::find($sedeId);
        $codigo = $sede ? strtoupper(substr($sede->codigo, 0, 3)) : 'GEN';
        $year = date('Y');
        $month = date('m');
        
        // Obtener el último número
        $last = self::whereYear('created_at', $year)
                    ->where('sede_id', $sedeId)
                    ->count();
        
        $numero = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        
        return "FAC-{$year}{$month}-{$codigo}-{$numero}";
    }

    /**
     * Marcar como recibida la mercancía y actualizar stock
     */
    public function marcarComoRecibida(): void
    {
        $this->recibido = true;
        $this->save();
        
        // Actualizar stock y registrar en Kardex
        $this->actualizarStockYkardex();
    }

    /**
     * Marcar como pagada la factura
     */
    public function marcarComoPagada(): void
    {
        $this->pagado = true;
        $this->save();
    }

    /**
     * Actualizar el stock en InventarioSede y registrar en Kardex
     */
    public function actualizarStockYkardex(): void
    {
        foreach ($this->items as $item) {
            // 1. Obtener o crear el stock en InventarioSede
            $stock = InventarioSede::firstOrCreate([
                'sede_id' => $this->sede_id,
                'producto_id' => $item->producto_id,
            ]);
            
            $saldoAnterior = $stock->cantidad_actual;
            $saldoNuevo = $saldoAnterior + $item->cantidad;
            
            // 2. Actualizar InventarioSede
            $stock->cantidad_actual = $saldoNuevo;
            $stock->ultima_actualizacion = now();
            $stock->updated_by = $this->usuario_id;
            $stock->save();
            
            // 3. Registrar en Kardex
            KardexMovimiento::create([
                'sede_id' => $this->sede_id,
                'producto_id' => $item->producto_id,
                'tipo_movimiento' => 'entrada_compra',
                'cantidad' => $item->cantidad,
                'saldo_anterior' => $saldoAnterior,
                'saldo_despues' => $saldoNuevo,
                'costo_unitario' => $item->precio_unitario,
                'costo_total' => $item->total,
                'documento_origen_type' => self::class,
                'documento_origen_id' => $this->id,
                'notas' => 'Compra: ' . $this->numero_factura,
                'created_by' => $this->usuario_id,
            ]);
        }
    }

    /**
     * Confirmar compra (recibida y pagada)
     */
    public function confirmarCompra(): void
    {
        $this->recibido = true;
        $this->pagado = true;
        $this->save();
        
        $this->actualizarStockYkardex();
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Filtrar por sede */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where('sede_id', $sedeId);
    }

    /** Filtrar por rango de fecha de factura */
    public function scopeEntreFechas($query, string $desde, string $hasta)
    {
        return $query->whereBetween('fecha_factura', [$desde, $hasta]);
    }

    /** Solo compras con registro tardío */
    public function scopeTardias($query)
    {
        return $query->where('registro_tardio', true);
    }

    /** Solo compras recibidas */
    public function scopeRecibidas($query)
    {
        return $query->where('recibido', true);
    }

    /** Solo compras no recibidas */
    public function scopePendientesRecibir($query)
    {
        return $query->where('recibido', false);
    }

    /** Solo compras pagadas */
    public function scopePagadas($query)
    {
        return $query->where('pagado', true);
    }

    /** Solo compras pendientes de pago */
    public function scopePendientesPago($query)
    {
        return $query->where('pagado', false);
    }
}