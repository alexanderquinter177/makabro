<?php

namespace App\Filament\Resources\AprobacionCompras\Pages;

use App\Filament\Resources\AprobacionCompras\AprobacionCompraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAprobacionCompras extends ListRecords
{
    protected static string $resource = AprobacionCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No se permite crear compras desde el listado de aprobaciones
        ];
    }
}
