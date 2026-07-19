<?php

namespace App\Filament\Resources\ProductoInsumos\Pages;

use App\Filament\Resources\ProductoInsumoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductoInsumo extends CreateRecord
{
    protected static string $resource = ProductoInsumoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
