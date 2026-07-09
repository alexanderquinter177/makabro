<?php

namespace App\Filament\Resources\Sedes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;

class SedesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                
                TextColumn::make('marca')
                    ->label('Marca')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                IconColumn::make('activo')
                    ->label('Activa')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('activo')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activas',
                        '0' => 'Inactivas',
                    ]),
                
                SelectFilter::make('marca')
                    ->label('Marca')
                    ->options(fn() => Sede::distinct()->pluck('marca', 'marca')->toArray()),
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