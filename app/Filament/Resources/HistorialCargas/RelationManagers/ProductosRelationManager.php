<?php

namespace App\Filament\Resources\HistorialCargas\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Filters\SelectFilter;

class ProductosRelationManager extends RelationManager
{
    protected static string $relationship = 'productos';

    protected static ?string $title = 'Detalle de Productos Cargados';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('📦 Información del Producto')
                    ->description('Datos técnicos y de catalogación del ítem cargado')
                    ->icon('heroicon-o-cube')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('codigo')
                                ->label('Código')
                                ->badge()
                                ->color('gray')
                                ->copyable()
                                ->icon('heroicon-o-qr-code'),

                            TextEntry::make('nombre_producto')
                                ->label('Nombre del Producto')
                                ->weight('bold')
                                ->size('lg')
                                ->icon('heroicon-o-cube'),

                            TextEntry::make('categoria')
                                ->label('Categoría')
                                ->badge()
                                ->color('info')
                                ->icon('heroicon-o-tag'),

                            TextEntry::make('tipo_producto')
                                ->label('Tipo de Producto')
                                ->badge()
                                ->color('warning')
                                ->icon('heroicon-o-rectangle-stack'),

                            TextEntry::make('unidad_medida')
                                ->label('Unidad de Medida')
                                ->badge()
                                ->color('gray')
                                ->icon('heroicon-o-scale'),
                                 TextEntry::make('cantidad')
                                ->label('Cantidad Cargada')
                                ->numeric(decimalPlaces: 2)
                                ->badge()
                                ->color('primary')
                                ->size('lg')
                                ->icon('heroicon-o-hashtag'),

                            TextEntry::make('precio')
                                ->label('Precio Unitario')
                                ->numeric(decimalPlaces: 2)
                                ->prefix('$')
                                ->color('gray')
                                ->size('lg')
                                ->icon('heroicon-o-tag'),

                            TextEntry::make('total_linea')
                                ->label('Total Línea (Cantidad x Precio)')
                                ->numeric(decimalPlaces: 2)
                                ->prefix('$')
                                ->weight('bold')
                                ->color('success')
                                ->size('lg')
                                ->icon('heroicon-o-calculator'),
                        ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre_producto')
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('Código copiado'),

                TextColumn::make('nombre_producto')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-cube')
                    ->iconColor('gray'),

                TextColumn::make('categoria')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('tipo_producto')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('unidad_medida')
                    ->label('U. Medida')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('precio')
                    ->label('Precio Unitario')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->prefix('$')
                    ->color('gray')
                    ->summarize(
                        Sum::make()
                            ->label('Total Precios')
                            ->prefix('$')
                            ->numeric(decimalPlaces: 2)
                    ),

                TextColumn::make('total_linea')
                    ->label('Total Línea')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->prefix('$')
                    ->weight('bold')
                    ->color('success')
                    ->summarize(
                        Sum::make()
                            ->label('Total Carga')
                            ->prefix('$')
                            ->numeric(decimalPlaces: 2)
                    ),
            ])
            ->defaultSort('id', 'asc')
            ->filters([
                SelectFilter::make('categoria')
                    ->label('Categoría')
                    ->options(fn () => \App\Models\Inventory\CargaProductoHistorial::query()->distinct()->pluck('categoria', 'categoria')->toArray()),

                SelectFilter::make('tipo_producto')
                    ->label('Tipo de Producto')
                    ->options(fn () => \App\Models\Inventory\CargaProductoHistorial::query()->distinct()->pluck('tipo_producto', 'tipo_producto')->toArray()),
            ])
            ->headerActions([])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver Detalle')
                    ->icon('heroicon-o-eye')
                    ->color('info'),
            ])
            ->bulkActions([])
            ->emptyStateIcon('heroicon-o-cube')
            ->emptyStateHeading('Sin productos en este historial')
            ->emptyStateDescription('No hay productos cargados en esta entrega.')
            ->striped();
    }
}
