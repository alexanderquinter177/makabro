<?php

namespace App\Filament\Resources\HistorialCargas\Pages;

use App\Filament\Resources\HistorialCargaResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHistorialCarga extends ViewRecord
{
    protected static string $resource = HistorialCargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimir')
                ->label('Imprimir Acta PDF')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn ($record): string => route('historial-cargas.imprimir', $record))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
