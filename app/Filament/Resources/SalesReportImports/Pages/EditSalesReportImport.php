<?php

namespace App\Filament\Resources\SalesReportImports\Pages;

use App\Filament\Resources\SalesReportImports\SalesReportImportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesReportImport extends EditRecord
{
    protected static string $resource = SalesReportImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
