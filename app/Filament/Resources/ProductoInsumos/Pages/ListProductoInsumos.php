<?php

namespace App\Filament\Resources\ProductoInsumos\Pages;

use App\Filament\Resources\ProductoInsumoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductoInsumos extends ListRecords
{
    protected static string $resource = ProductoInsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
