<?php

namespace App\Filament\Resources\ProductoSubensambles\Pages;

use App\Filament\Resources\ProductoSubensambleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductoSubensamble extends CreateRecord
{
    protected static string $resource = ProductoSubensambleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
