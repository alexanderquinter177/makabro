<?php

namespace App\Filament\Resources\InventarioSedes\Pages;

use App\Filament\Resources\InventarioSedeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventarioSede extends EditRecord
{
    protected static string $resource = InventarioSedeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
