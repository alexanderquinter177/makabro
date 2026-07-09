<?php

namespace App\Filament\Resources\Inventarios\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;

class InventarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información principal
                        Section::make('Información del Inventario')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                Select::make('sede_id')
                                    ->label('Sede')
                                    ->options(Sede::where('activo', true)->pluck('nombre', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Seleccione la sede')
                                    ->prefixIcon('heroicon-o-building-office-2')
                                    ->helperText('Sede donde se realiza el inventario')
                                    ->default(fn () => auth()->user()?->sede_actual?->id ?? null)
                                    ->disabled(true)
                                    ->dehydrated(),

                                Select::make('usuario_id')
                                    ->label('Usuario Responsable')
                                    ->options(User::where('activo', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->default(fn () => auth()->id())
                                    ->placeholder('Seleccione el usuario')
                                    ->prefixIcon('heroicon-o-user')
                                    ->helperText('Usuario que realiza el inventario')
                                    ->disabled(true)
                                    ->dehydrated(),

                                DatePicker::make('fecha_inventario')
                                    ->label('Fecha del Inventario')
                                    ->required()
                                    ->default(now())
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->displayFormat('d/m/Y')
                                    ->helperText('Fecha en la que se realiza el inventario'),

                                Select::make('tipo_inventario')
                                    ->label('Tipo de Inventario')
                                    ->options([
                                        'diario' => '📅 Diario',
                                        'semanal' => '📆 Semanal',
                                        'mensual' => '📊 Mensual',
                                        'completo' => '📋 Completo',
                                        'parcial' => '✏️ Parcial',
                                    ])
                                    ->required()
                                    ->default('diario')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->helperText('Tipo de inventario a realizar'),
                            ])
                            ->columnSpan(1),

                        // COLUMNA DERECHA: Información adicional
                        Section::make('Información Adicional')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Select::make('area')
                                    ->label('Área')
                                    ->options([
                                        'cocina' => '🍳 Cocina',
                                        'barra' => '🍸 Barra',
                                        'administración' => '📋 Administración',
                                        'bodega' => '📦 Bodega',
                                        'restaurante' => '🍽️ Restaurante',
                                        'todas' => '🏢 Todas las áreas',
                                    ])
                                    ->placeholder('Seleccione el área')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->helperText('Área específica del inventario'),

                                TextInput::make('codigo_inventario')
                                    ->label('Código de Inventario')
                                    ->maxLength(50)
                                    ->placeholder('INV-2024-0001')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->helperText('Código único de referencia')
                                    ->default(function () {
                                        return 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                                    })
                                    ->disabled(false)
                                    ->dehydrated(),

                                Toggle::make('completado')
                                    ->label('Inventario Completado')
                                    ->default(false)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('Marca el inventario como completado'),

                                TextInput::make('valor_total')
                                    ->label('Valor Total')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$ ')
                                    ->placeholder('0.00')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->helperText('Suma total de los productos inventariados')
                                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),

                // SECCIÓN ABAJO: Observaciones
                Section::make('Observaciones y Notas')
                    ->icon('heroicon-o-document-text')
                    ->description('Detalles adicionales sobre el inventario')
                    ->schema([
                        Textarea::make('notas')
                            ->label('Notas / Observaciones')
                            ->maxLength(65535)
                            ->placeholder('Ingrese cualquier observación relevante sobre el inventario...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none']),

                        Textarea::make('observaciones_internas')
                            ->label('Observaciones Internas')
                            ->maxLength(65535)
                            ->placeholder('Notas internas (solo visibles para administradores)...')
                            ->rows(2)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none'])
                            ->hidden(fn () => !auth()->user()?->hasRole('super_admin') && !auth()->user()?->hasRole('admin')),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}