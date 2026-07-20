<?php

namespace App\Filament\Resources\SalesReportImports\Pages;

use App\Filament\Resources\SalesReportImports\SalesReportImportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesReportImport extends ViewRecord
{
    protected static string $resource = SalesReportImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('danger'),
        ];
    }
}
