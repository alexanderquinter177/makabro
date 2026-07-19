<?php

namespace App\Filament\Resources\ProductoVentas\Pages;

use App\Filament\Resources\ProductoVentaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductoVentas extends ListRecords
{
    protected static string $resource = ProductoVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
