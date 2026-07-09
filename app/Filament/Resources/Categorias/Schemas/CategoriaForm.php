<?php

namespace App\Filament\Resources\Categorias\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Str;

class CategoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información principal
                        Section::make('Información de la Categoría')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Carnes, Bebidas, Pastas')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),
                                
                                TextInput::make('slug')
                                    ->label('Slug / URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('ej-carnes-bebidas')
                                    ->prefixIcon('heroicon-o-link')
                                    ->helperText('Identificador único para la URL')
                                    ->disabled(fn (string $operation) => $operation === 'edit'),
                                
                                ColorPicker::make('color')
                                    ->label('Color')
                                    ->placeholder('#000000')
                                    ->prefixIcon('heroicon-o-paint-brush')
                                    ->helperText('Color para identificar la categoría'),

                                     Toggle::make('activo')
                                    ->label('Categoría Activa')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('Desactiva para ocultar la categoría del sistema'),

                                     Textarea::make('descripcion')
                            ->label('Descripción')
                            ->maxLength(500)
                            ->placeholder('Descripción de la categoría...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none']),
                            ]),                     
                    ])
                    ->columnSpanFull(),
            
            ]);
    }
}