<?php

namespace App\Filament\Resources\ProductoVentas\Pages;

use App\Filament\Resources\ProductoVentaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductoVenta extends CreateRecord
{
    protected static string $resource = ProductoVentaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
