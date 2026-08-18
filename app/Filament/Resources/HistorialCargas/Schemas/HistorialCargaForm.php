<?php

namespace App\Filament\Resources\HistorialCargas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class HistorialCargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General de la Carga')
                    ->description('Detalles principales del maestro de carga de productos')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('sede_id')
                                ->label('Sede')
                                ->options(\App\Models\Catalog\Sede::where('activo', true)->pluck('nombre', 'id'))
                                ->required()
                                ->searchable()
                                ->default(fn () => session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id)
                                ->disabled(true)
                                ->dehydrated()
                                ->prefixIcon('heroicon-o-building-office-2'),

                            DatePicker::make('fecha')
                                ->label('Fecha de Carga')
                                ->required()
                                ->default(now())
                                ->prefixIcon('heroicon-o-calendar'),

                            Select::make('tipo')
                                ->label('Tipo de Entrega')
                                ->options([
                                    'Entrega de barra' => 'Entrega de barra',
                                    'Entrega de cocina' => 'Entrega de cocina',
                                ])
                                ->required()
                                ->prefixIcon('heroicon-o-tag'),

                            Select::make('cargo_recibe')
                                ->label('Cargo de Quien Recibe')
                                ->options([
                                    'Líder de cocina' => 'Líder de cocina',
                                    'Administrador' => 'Administrador',
                                ])
                                ->required()
                                ->prefixIcon('heroicon-o-briefcase'),

                            TextInput::make('nombre_recibe')
                                ->label('Nombre de Quien Recibe')
                                ->placeholder('EJ: CARLOS GÓMEZ')
                                ->required()
                                ->maxLength(255)
                                ->prefixIcon('heroicon-o-user')
                                ->dehydrateStateUsing(fn ($state) => mb_strtoupper(trim($state ?? ''), 'UTF-8'))
                                ->afterStateUpdated(fn ($set, $state) => $set('nombre_recibe', mb_strtoupper($state ?? '', 'UTF-8'))),

                            TextInput::make('valor_total')
                                ->label('Valor Total Calculado')
                                ->numeric()
                                ->prefix('$')
                                ->readOnly()
                                ->default(0)
                                ->extraInputAttributes(['class' => 'font-bold text-lg text-emerald-600']),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
