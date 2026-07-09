<?php

namespace App\Filament\Resources\Sedes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;

class SedeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información principal
                        Section::make('Información Principal')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre de la Sede')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Makabro Bello')
                                    ->prefixIcon('heroicon-o-building-office-2')
                                    ->helperText('Nombre completo de la sede')
                                    ->columnSpanFull(),
                                
                                TextInput::make('codigo')
                                    ->label('Código')
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: MBE')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->helperText('Código único de la sede (máx. 10 caracteres)'),
                                
                                TextInput::make('marca')
                                    ->label('Marca / Franquicia')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Makabro, Carmela, etc.')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->helperText('Marca o franquicia a la que pertenece'),
                            ])
                            ->columnSpan(1),

                        // COLUMNA DERECHA: Contacto y ubicación
                        Section::make('Contacto y Ubicación')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                TextInput::make('direccion')
                                    ->label('Dirección')
                                    ->maxLength(255)
                                    ->placeholder('Calle 123 #45-67')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->helperText('Dirección física de la sede')
                                    ->columnSpanFull(),
                                
                                TextInput::make('telefono')
                                    ->label('Teléfono')
                                    ->maxLength(20)
                                    ->placeholder('(604) 123-4567')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->tel()
                                    ->helperText('Teléfono de contacto'),
                                
                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('sede@makabro.com')
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->helperText('Correo electrónico de la sede'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),

                // SECCIÓN: Información adicional
                Section::make('Información Adicional')
                    ->icon('heroicon-o-information-circle')
                    ->description('Configuración y estado de la sede')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('activo')
                                    ->label('Sede Activa')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('Desactiva para ocultar la sede del sistema')
                                    ->columnSpan(1),

                                TextInput::make('nit')
                                    ->label('NIT')
                                    ->maxLength(20)
                                    ->placeholder('Ej: 900.123.456-7')
                                    ->prefixIcon('heroicon-o-document-text')
                                    ->helperText('Número de identificación tributaria')
                                    ->columnSpan(1),
                            ]),
                        
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->maxLength(500)
                            ->placeholder('Información adicional sobre la sede...')
                            ->rows(2)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none']),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}