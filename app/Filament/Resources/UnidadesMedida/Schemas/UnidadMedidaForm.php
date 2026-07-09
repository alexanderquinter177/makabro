<?php

namespace App\Filament\Resources\UnidadesMedida\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class UnidadMedidaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información principal
                        Section::make('INFORMACIÓN DE LA UNIDAD')
                            ->icon('heroicon-o-scale')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('NOMBRE')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('EJ: KILOGRAMO, LITRO, UNIDAD')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->helperText('Nombre completo de la unidad de medida')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('nombre', strtoupper($state))),
                                
                                TextInput::make('abreviatura')
                                    ->label('ABREVIATURA')
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('EJ: KG, L, UND')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->helperText('Abreviatura única de la unidad (máx. 10 caracteres)')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('abreviatura', strtoupper($state))),
                            ])
                            ->columnSpan(1),

                        // COLUMNA DERECHA: Estado
                        Section::make('ESTADO')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Toggle::make('activo')
                                    ->label('UNIDAD ACTIVA')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('Desactiva para ocultar esta unidad del sistema'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}