<?php

namespace App\Filament\Resources\Auditorias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;

class AuditoriasTable
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
                    ->label('Auditor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_auditoria')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('tipo_auditoria')
                    ->label('Tipo')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'en_progreso' => 'primary',
                        'completada' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('diferencia_encontrada')
                    ->label('Diferencia')
                    ->money('COP')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->options(Sede::pluck('nombre', 'id')->toArray()),

                SelectFilter::make('usuario_id')
                    ->label('Auditor')
                    ->options(User::pluck('name', 'id')->toArray()),

                SelectFilter::make('tipo_auditoria')
                    ->label('Tipo')
                    ->options([
                        'sorpresa' => 'Sorpresa',
                        'programada' => 'Programada',
                        'arqueo' => 'Arqueo',
                    ]),

                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_progreso' => 'En Progreso',
                        'completada' => 'Completada',
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
