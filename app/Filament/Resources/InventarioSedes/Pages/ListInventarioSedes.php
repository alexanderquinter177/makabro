<?php

namespace App\Filament\Resources\InventarioSedes\Pages;

use App\Filament\Resources\InventarioSedeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventarioSedes extends ListRecords
{
    protected static string $resource = InventarioSedeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
