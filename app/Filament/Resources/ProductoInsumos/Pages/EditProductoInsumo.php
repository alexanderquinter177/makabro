<?php

namespace App\Filament\Resources\ProductoInsumos\Pages;

use App\Filament\Resources\ProductoInsumoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductoInsumo extends EditRecord
{
    protected static string $resource = ProductoInsumoResource::class;

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
