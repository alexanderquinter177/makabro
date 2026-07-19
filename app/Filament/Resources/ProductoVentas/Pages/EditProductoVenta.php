<?php

namespace App\Filament\Resources\ProductoVentas\Pages;

use App\Filament\Resources\ProductoVentaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductoVenta extends EditRecord
{
    protected static string $resource = ProductoVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
