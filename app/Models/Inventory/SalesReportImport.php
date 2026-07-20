<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Catalog\Sede;

class SalesReportImport extends Model
{
    protected $table = 'sales_report_imports';

    protected $fillable = [
        'sede_id',
        'date_range',
        'file_name',
    ];

    /**
     * Sede vinculada al reporte de importación.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    /**
     * Detalles del reporte de ventas.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SalesReportImportItem::class, 'import_id');
    }
}
