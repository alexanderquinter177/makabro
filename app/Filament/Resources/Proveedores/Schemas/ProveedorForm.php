<?php

namespace App\Filament\Resources\Proveedores\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ProveedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información principal
                        Section::make('Información Principal')
                            ->icon('heroicon-o-truck')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('NOMBRE / RAZÓN SOCIAL')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('EJ: CARNES EL NOVILLO S.A.S.')
                                    ->prefixIcon('heroicon-o-building-storefront')
                                    ->helperText('Nombre completo de la empresa')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('nombre', strtoupper($state))),
                                
                                TextInput::make('nit')
                                    ->label('NIT')
                                    ->maxLength(50)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('EJ: 900.123.456-7')
                                    ->prefixIcon('heroicon-o-document-text')
                                    ->helperText('Número de identificación tributaria')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('nit', strtoupper($state))),
                                
                                TextInput::make('persona_contacto')
                                    ->label('PERSONA DE CONTACTO')
                                    ->maxLength(255)
                                    ->placeholder('EJ: JUAN PÉREZ')
                                    ->prefixIcon('heroicon-o-user')
                                    ->helperText('Persona encargada de las ventas')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('persona_contacto', strtoupper($state))),
                                
                                TextInput::make('telefono')
                                    ->label('TELÉFONO')
                                    ->tel()
                                    ->maxLength(50)
                                    ->placeholder('EJ: 3001234567')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->helperText('Teléfono de contacto')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('telefono', strtoupper($state))),
                            ])
                            ->columnSpan(1),

                        // COLUMNA DERECHA: Contacto y estado
                        Section::make('Contacto y Estado')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('email')
                                    ->label('CORREO ELECTRÓNICO')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('EJ: VENTAS@PROVEEDOR.COM')
                                    ->prefixIcon('heroicon-o-envelope')
                                    ->helperText('Correo electrónico de contacto')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('email', strtoupper($state))),
                                
                                TextInput::make('direccion')
                                    ->label('DIRECCIÓN')
                                    ->maxLength(255)
                                    ->placeholder('EJ: CALLE 45 # 12 - 34')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->helperText('Dirección física del proveedor')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('direccion', strtoupper($state))),
                                
                                TextInput::make('website')
                                    ->label('SITIO WEB')
                                    ->maxLength(255)
                                    ->placeholder('EJ: WWW.PROVEEDOR.COM')
                                    ->prefixIcon('heroicon-o-globe-alt')
                                    ->helperText('Página web del proveedor')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('website', strtoupper($state))),
                                
                                Toggle::make('activo')
                                    ->label('PROVEEDOR ACTIVO')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('Habilita o deshabilita este proveedor para compras y registros'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),

                // SECCIÓN: Información adicional
                Section::make('Información Adicional')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Textarea::make('observaciones')
                            ->label('OBSERVACIONES')
                            ->maxLength(500)
                            ->placeholder('INFORMACIÓN ADICIONAL SOBRE EL PROVEEDOR...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none'])
                            ->formatStateUsing(fn ($state) => strtoupper($state))
                            ->afterStateUpdated(fn ($set, $state) => $set('observaciones', strtoupper($state))),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}