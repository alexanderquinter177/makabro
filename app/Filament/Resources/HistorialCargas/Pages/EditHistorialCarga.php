<?php

namespace App\Filament\Resources\HistorialCargas\Pages;

use App\Filament\Resources\HistorialCargaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHistorialCarga extends EditRecord
{
    protected static string $resource = HistorialCargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
