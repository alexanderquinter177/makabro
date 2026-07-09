<?php

namespace App\Filament\Resources\Novedades\Pages;

use App\Filament\Resources\NovedadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNovedades extends ListRecords
{
    protected static string $resource = NovedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
