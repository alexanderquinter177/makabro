<?php

namespace App\Filament\Resources\Compras\Pages;

use App\Filament\Resources\CompraResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCompra extends ViewRecord
{
    protected static string $resource = CompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make()
                ->visible(fn () => $this->record->status === 'borrador'),
        ];
    }
}
