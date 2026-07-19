<?php

namespace App\Filament\Resources\ProductoSubensambles\Pages;

use App\Filament\Resources\ProductoSubensambleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductoSubensamble extends EditRecord
{
    protected static string $resource = ProductoSubensambleResource::class;

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
