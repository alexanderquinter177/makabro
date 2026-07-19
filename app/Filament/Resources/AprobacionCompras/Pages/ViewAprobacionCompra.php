<?php

namespace App\Filament\Resources\AprobacionCompras\Pages;

use App\Filament\Resources\AprobacionCompras\AprobacionCompraResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAprobacionCompra extends ViewRecord
{
    protected static string $resource = AprobacionCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('aprobar')
                ->label('Aprobar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('¿Aprobar esta compra/factura?')
                ->modalDescription('Esta acción cambiará el estado de la compra a "Aprobado", sumará el stock de los productos en la sede correspondiente y registrará los movimientos en el Kardex. Esta acción no se puede deshacer.')
                ->visible(fn () => $this->record->status === 'pendiente' && auth()->user()?->hasPermissionTo('compra.aprobar'))
                ->action(function () {
                    $this->record->aprobar();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Compra aprobada')
                        ->body('El stock de los productos ha sido actualizado y se han registrado los movimientos en el Kardex.')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            \Filament\Actions\Action::make('devolver')
                ->label('Devolver a Borrador')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('¿Devolver esta compra a Borrador?')
                ->modalDescription('Esta acción cambiará el estado de la compra a "Borrador" para que el usuario creador pueda modificarla o corregirla.')
                ->visible(fn () => $this->record->status === 'pendiente' && auth()->user()?->hasPermissionTo('compra.aprobar'))
                ->action(function () {
                    $this->record->status = 'borrador';
                    $this->record->save();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Compra devuelta')
                        ->body('La compra ha sido devuelta a estado borrador para su edición.')
                        ->warning()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            \Filament\Actions\Action::make('rechazar')
                ->label('Rechazar Compra')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('¿Rechazar esta compra?')
                ->modalDescription('Esta acción marcará la compra como "Rechazada". No se actualizará el stock ni se registrarán movimientos de Kardex. Esta acción es definitiva.')
                ->visible(fn () => $this->record->status === 'pendiente' && auth()->user()?->hasPermissionTo('compra.aprobar'))
                ->action(function () {
                    $this->record->status = 'rechazado';
                    $this->record->save();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Compra rechazada')
                        ->body('La compra ha sido rechazada y guardada como histórico.')
                        ->danger()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}
