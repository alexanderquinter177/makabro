<?php

namespace App\Filament\Resources\HistorialCargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;

class HistorialCargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tipo')
                    ->label('Tipo de Entrega')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Entrega de barra' => 'info',
                        'Entrega de cocina' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cargo_recibe')
                    ->label('Cargo Recibe')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nombre_recibe')
                    ->label('Nombre Recibe')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->numeric(decimalPlaces: 2)
                    ->prefix('$')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('productos_count')
                    ->label('Ítems')
                    ->counts('productos')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Tipo de Entrega')
                    ->options([
                        'Entrega de barra' => 'Entrega de barra',
                        'Entrega de cocina' => 'Entrega de cocina',
                    ]),

                SelectFilter::make('cargo_recibe')
                    ->label('Cargo Recibe')
                    ->options([
                        'LÍDER DE COCINA' => 'LÍDER DE COCINA',
                        'ADMINISTRADOR' => 'ADMINISTRADOR',
                    ]),

                SelectFilter::make('nombre_recibe')
                    ->label('Nombre Recibe')
                    ->options(fn () => \App\Models\Inventory\CargaHistorial::query()
                        ->whereNotNull('nombre_recibe')
                        ->distinct()
                        ->pluck('nombre_recibe', 'nombre_recibe')
                        ->toArray()),
            ])
            ->recordActions([
                Action::make('imprimir')
                    ->label('Imprimir Acta')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (\App\Models\Inventory\CargaHistorial $record): string => route('historial-cargas.imprimir', $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-archive-box')
            ->emptyStateHeading('Sin registros de cargas')
            ->emptyStateDescription('Utiliza la acción "Importar Carga CSV" para registrar una nueva carga de productos.');
    }
}
