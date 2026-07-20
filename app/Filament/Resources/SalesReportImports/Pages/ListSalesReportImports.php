<?php

namespace App\Filament\Resources\SalesReportImports\Pages;

use App\Filament\Resources\SalesReportImports\SalesReportImportResource;
use Filament\Resources\Pages\ListRecords;

class ListSalesReportImports extends ListRecords
{
    protected static string $resource = SalesReportImportResource::class;

    protected function getHeaderActions(): array
    {
        return []; // La importación se hace desde el botón de la tabla (headerActions)
    }
}
