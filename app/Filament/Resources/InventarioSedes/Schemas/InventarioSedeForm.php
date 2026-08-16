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
                            ->options(Sede::where('activo', true)->pluck('nombre', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Seleccione la sede')
                            ->disabled(true)
                            ->dehydrated()
                            ->default(fn () => session('sede_id')),

                        Select::make('producto_id')
                            ->label('Producto / Insumo')
                            ->options(function (callable $get) {
                                $sedeId = $get('sede_id') ?? session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;
                                $query = Producto::withoutGlobalScope('sede')->where('activo', true);
                                if ($sedeId) {
                                    $query->where('sede_id', $sedeId);
                                }
                                return $query->orderBy('nombre')->pluck('nombre', 'id');
                            })
                            ->getSearchResultsUsing(function (string $search, callable $get) {
                                $sedeId = $get('sede_id') ?? session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;
                                $query = Producto::withoutGlobalScope('sede')
                                    ->where('activo', true)
                                    ->where('nombre', 'like', "%{$search}%");
                                if ($sedeId) {
                                    $query->where('sede_id', $sedeId);
                                }
                                return $query->limit(50)->pluck('nombre', 'id');
                            })
                            ->getOptionLabelUsing(fn ($value) => Producto::withoutGlobalScope('sede')->find($value)?->nombre)
                            ->required()
                            ->searchable()
                            ->placeholder('Seleccione el producto')
                            ->unique(
                                table: 'inventario_sedes',
                                column: 'producto_id',
                                ignoreRecord: true,
                                modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule, callable $get) => $rule->where('sede_id', $get('sede_id'))
                            )
                            ->validationMessages([
                                'unique' => 'Este producto ya cuenta con un registro de inventario en la sede seleccionada.',
                            ]),

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
