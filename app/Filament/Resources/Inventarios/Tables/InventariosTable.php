<?php

namespace App\Filament\Resources\Inventarios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;

class InventariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('usuario.name')
                    ->label('Registrado por')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_inventario')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('area')
                    ->label('Área')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('tipo_inventario')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diario' => 'success',
                        'mensual' => 'warning',
                        'completo' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('usuario_id')
                    ->label('Usuario')
                    ->options(User::pluck('name', 'id')->toArray()),

                SelectFilter::make('area')
                    ->label('Área')
                    ->options([
                        'cocina' => 'Cocina',
                        'barra' => 'Barra',
                        'administración' => 'Administración',
                    ]),

                SelectFilter::make('tipo_inventario')
                    ->label('Tipo')
                    ->options([
                        'diario' => 'Diario',
                        'mensual' => 'Mensual',
                        'completo' => 'Completo',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
