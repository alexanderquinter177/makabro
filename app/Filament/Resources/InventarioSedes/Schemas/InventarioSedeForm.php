<?php

namespace App\Filament\Resources\InventarioSedes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;

class InventarioSedeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Control de Stock en Sede')
                    ->schema([
                        Select::make('sede_id')
                            ->label('Sede')
                            ->options(Sede::pluck('nombre', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Seleccione la sede'),

                        Select::make('producto_id')
                            ->label('Producto / Insumo')
                            ->options(Producto::pluck('nombre', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Seleccione el producto'),

                        TextInput::make('cantidad_actual')
                            ->label('Cantidad Actual')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->placeholder('0.00'),

                        TextInput::make('stock_minimo')
                            ->label('Stock Mínimo')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->placeholder('0.00'),

                        TextInput::make('stock_maximo')
                            ->label('Stock Máximo')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->placeholder('0.00'),

                        TextInput::make('punto_reorden')
                            ->label('Punto de Reorden')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->placeholder('0.00'),
                    ])->columns(2),
            ]);
    }
}
