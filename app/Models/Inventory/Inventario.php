<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditSignature;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;
use App\Models\Inventory\InventarioItem;

class Inventario extends Model
{
    use SoftDeletes, HasAuditSignature;

    protected $table = 'inventarios';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'sede_id',
        'usuario_id',
        'fecha_inventario',
        'area',
        'tipo_inventario',
        'codigo_inventario',
        'valor_total',
        'completado',
        'notas',
        'observaciones_internas',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Conversiones de tipos.
     */
    protected $casts = [
        'fecha_inventario' => 'date',
        'valor_total' => 'decimal:2',
        'completado' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------------------------

    /** Sede del inventario */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /** Usuario que realizó el inventario */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /** Ítems (productos contados) de este inventario */
    public function items(): HasMany
    {
        return $this->hasMany(InventarioItem::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Filtrar por sede */
    public function scopeDeSede($query, int $sedeId)
    {
        return $query->where($this->getTable() . '.sede_id', $sedeId);
    }

    /** Filtrar por tipo de inventario */
    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo_inventario', $tipo);
    }

    /** Filtrar por rango de fecha */
    public function scopeEntreFechas($query, string $desde, string $hasta)
    {
        return $query->whereBetween('fecha_inventario', [$desde, $hasta]);
    }

    /** Filtrar por área */
    public function scopeDeArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    /** Solo inventarios completados */
    public function scopeCompletados($query)
    {
        return $query->where('completado', true);
    }

    /** Solo inventarios pendientes */
    public function scopePendientes($query)
    {
        return $query->where('completado', false);
    }

    // -------------------------------------------------------------------------
    // Métodos
    // -------------------------------------------------------------------------

    /** Calcular el valor total del inventario */
    public function calcularValorTotal()
    {
        $this->valor_total = $this->items->sum('valor_total');
        $this->save();
        return $this->valor_total;
    }

    /** Completar el inventario y actualizar stock */
    public function completar()
    {
        $this->completado = true;
        $this->save();
        
        // Actualizar el stock de la sede
        $this->actualizarStockSede();
        
        return $this;
    }

    /** Actualizar el stock en inventario_sede con los valores contados */
    public function actualizarStockSede()
    {
        foreach ($this->items as $item) {
            $stock = InventarioSede::firstOrCreate([
                'sede_id' => $this->sede_id,
                'producto_id' => $item->producto_id,
            ]);
            
            $stock->cantidad_actual = $item->cantidad_contada;
            $stock->ultima_actualizacion = now();
            $stock->updated_by = $this->usuario_id;
            $stock->save();
        }
        
        return $this;
    }

    /** Generar código de inventario */
    public static function generarCodigo($sedeCodigo = 'GEN')
    {
        $fecha = date('Ymd');
        $secuencia = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return 'INV-' . $fecha . '-' . strtoupper($sedeCodigo) . '-' . $secuencia;
    }
}