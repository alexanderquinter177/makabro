<?php

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre del Permiso')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(function ($state) {
                        $parts = explode('.', $state);
                        $module = Str::title(str_replace('_', ' ', $parts[0] ?? ''));
                        $action = Str::title(str_replace('_', ' ', $parts[1] ?? ''));
                        return $module . ' › ' . $action;
                    })
                    ->tooltip(function ($state) {
                        return $state;
                    }),

                TextColumn::make('module')
                    ->label('Módulo')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(function ($record) {
                        $parts = explode('.', $record->name);
                        return Str::title(str_replace('_', ' ', $parts[0] ?? ''));
                    }),

                TextColumn::make('action')
                    ->label('Acción')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(function ($record) {
                        $parts = explode('.', $record->name);
                        return Str::title(str_replace('_', ' ', $parts[1] ?? ''));
                    }),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->badge()
                    ->color('gray'),

                TextColumn::make('roles_count')
                    ->label('Roles Asignados')
                    ->counts('roles')
                    ->badge()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('module')
                    ->label('Módulo')
                    ->options(function () {
                        $permissions = Permission::all();
                        $modules = $permissions->map(function ($permission) {
                            $parts = explode('.', $permission->name);
                            return $parts[0] ?? null;
                        })->filter()->unique()->values();

                        return $modules->mapWithKeys(function ($module) {
                            return [$module => Str::title(str_replace('_', ' ', $module))];
                        })->toArray();
                    })
                    ->preload(),

                SelectFilter::make('guard_name')
                    ->label('Guard')
                    ->options([
                        'web' => 'Web',
                        'api' => 'API',
                    ])
                    ->preload(),

                Filter::make('has_roles')
                    ->label('Con roles asignados')
                    ->query(fn ($query) => $query->has('roles')),

                Filter::make('no_roles')
                    ->label('Sin roles asignados')
                    ->query(fn ($query) => $query->doesntHave('roles')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver'),
                EditAction::make()
                    ->label('Editar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Eliminar seleccionados'),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->poll('10s');
    }
}