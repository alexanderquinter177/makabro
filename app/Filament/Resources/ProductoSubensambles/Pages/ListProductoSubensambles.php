<?php

namespace App\Filament\Resources\ProductoSubensambles\Pages;

use App\Filament\Resources\ProductoSubensambleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductoSubensambles extends ListRecords
{
    protected static string $resource = ProductoSubensambleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
