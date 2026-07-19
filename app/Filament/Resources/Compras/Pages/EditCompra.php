<?php

namespace App\Filament\Resources\Compras\Pages;

use App\Filament\Resources\CompraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompra extends EditRecord
{
    protected static string $resource = CompraResource::class;

    protected function beforeFill(): void
    {
        if ($this->record->status !== 'borrador') {
            \Filament\Notifications\Notification::make()
                ->title('Acción no permitida')
                ->body('Solo se pueden editar compras en estado borrador.')
                ->danger()
                ->send();

            $this->redirect($this->getResource()::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn () => $this->record->status === 'borrador'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
