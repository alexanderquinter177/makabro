<?php

namespace App\Filament\Resources\SalesReportImports\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Filters\TernaryFilter;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = '📦 Ítems del Reporte';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Estado del ítem ─────────────────────────────────────────────
                Section::make('Estado de Procesamiento')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('producto_nombre')
                                ->label('Producto (del Excel)')
                                ->weight('bold')
                                ->size('lg')
                                ->icon('heroicon-o-cube'),

                            TextEntry::make('estado_carga')
                                ->label('Estado')
                                ->state(fn ($record) => $record->product_id ? 'Procesado ✅' : 'Sin stock en sede ⚠️')
                                ->badge()
                                ->color(fn ($record) => $record->product_id ? 'success' : 'danger'),

                            TextEntry::make('product_id')
                                ->label('ID Producto Cruzado')
                                ->default('—')
                                ->color('gray'),
                        ]),
                    ]),

                // ── Cantidades ───────────────────────────────────────────────────
                Section::make('📦 Cantidades')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Grid::make(4)->schema([
                            TextEntry::make('cantidad_venta')
                                ->label('Cantidad Venta')
                                ->numeric(2)
                                ->badge()
                                ->color('primary'),

                            TextEntry::make('cortesia')
                                ->label('Cortesía')
                                ->numeric(2),

                            TextEntry::make('hora_feliz')
                                ->label('Hora Feliz')
                                ->numeric(2),

                            TextEntry::make('consumo')
                                ->label('Consumo')
                                ->numeric(2),

                            TextEntry::make('baja_dano')
                                ->label('Baja / Daño')
                                ->numeric(2)
                                ->color('danger'),

                            TextEntry::make('unidad')
                                ->label('Unidad de Medida')
                                ->badge()
                                ->color('gray'),

                            TextEntry::make('punto_operacion')
                                ->label('Punto de Operación'),

                            TextEntry::make('grupo')
                                ->label('Grupo'),
                        ]),
                    ]),

                // ── Valores Financieros ──────────────────────────────────────────
                Section::make('💰 Información Financiera')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('v_unitario')
                                ->label('Valor Unitario')
                                ->numeric(2)
                                ->prefix('$')
                                ->badge()
                                ->color('gray'),

                            TextEntry::make('venta_bruta')
                                ->label('Venta Bruta')
                                ->numeric(2)
                                ->prefix('$'),

                            TextEntry::make('descuento')
                                ->label('Descuento')
                                ->numeric(2)
                                ->prefix('$')
                                ->color('warning'),

                            TextEntry::make('venta_neta')
                                ->label('Venta Neta')
                                ->numeric(2)
                                ->prefix('$')
                                ->weight('bold')
                                ->color('success'),

                            TextEntry::make('impuesto')
                                ->label('Impuesto')
                                ->numeric(2)
                                ->prefix('$'),

                            TextEntry::make('total')
                                ->label('Total')
                                ->numeric(2)
                                ->prefix('$')
                                ->weight('bold')
                                ->size('lg')
                                ->badge()
                                ->color('success'),

                            TextEntry::make('porcentaje')
                                ->label('Porcentaje')
                                ->numeric(2)
                                ->suffix('%')
                                ->badge()
                                ->color('info'),
                        ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('producto_nombre')
            ->columns([
                TextColumn::make('producto_nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon(fn ($record) => $record->product_id ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle')
                    ->iconColor(fn ($record) => $record->product_id ? 'success' : 'danger')
                    ->color(fn ($record) => $record->product_id ? 'success' : 'danger')
                    ->description(fn ($record) => $record->product_id
                        ? '✅ Procesado en inventario'
                        : '⚠️ No tiene stock registrado en esta sede'),

                TextColumn::make('cantidad_venta')
                    ->label('Cant. Venta')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('v_unitario')
                    ->label('V. Unitario')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->prefix('$')
                    ->color('gray'),

                TextColumn::make('venta_neta')
                    ->label('Venta Neta')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->prefix('$')
                    ->weight('semibold'),

                TextColumn::make('total')
                    ->label('Total')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->prefix('$')
                    ->weight('bold')
                    ->badge()
                    ->color('success'),

                TextColumn::make('porcentaje')
                    ->label('%')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->suffix('%')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('grupo')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('unidad')
                    ->label('Unidad')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('cortesia')
                    ->label('Cortesía')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('hora_feliz')
                    ->label('Hora Feliz')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('consumo')
                    ->label('Consumo')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('baja_dano')
                    ->label('Baja/Daño')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('total', 'desc')
            ->filters([
                TernaryFilter::make('product_id')
                    ->label('Estado de carga')
                    ->nullable()
                    ->trueLabel('Solo procesados ✅')
                    ->falseLabel('Solo sin stock ⚠️')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('product_id'),
                        false: fn ($query) => $query->whereNull('product_id'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->headerActions([])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([])
            ->emptyStateIcon('heroicon-o-table-cells')
            ->emptyStateHeading('Sin ítems en este reporte')
            ->striped();
    }
}
