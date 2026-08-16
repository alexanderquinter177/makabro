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

    protected static function booted()
    {
        static::deleting(function ($compra) {
            if ($compra->status !== 'borrador') {
                throw new \Exception('Solo es posible eliminar compras en estado borrador.');
            }
        });
    }

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
        'total',
        'imagen_factura',
        'status',
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
        // El stock, Kardex y el catálogo ahora se actualizan automáticamente a nivel de modelo en CompraItem::booted()
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

    /**
     * Aprobar la compra, cambiar estado y procesar stock/Kardex de todos sus ítems
     */
    public function aprobar(): void
    {
        if ($this->status === 'aprobado') {
            return;
        }

        $this->status = 'aprobado';
        $this->save();

        foreach ($this->items as $item) {
            $item->procesarIngresoStock();
        }
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Filtrar por sede */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where($this->getTable() . '.sede_id', $sedeId);
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