<?php

namespace App\Filament\Resources\SalesReportImports\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Models\Catalog\Sede;

class SalesReportImportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Reporte de Ventas')
                    ->schema([
                        Select::make('sede_id')
                            ->label('Sede')
                            ->options(Sede::pluck('nombre', 'id'))
                            ->disabled(),

                        TextInput::make('date_range')
                            ->label('Rango de Fechas')
                            ->disabled(),

                        TextInput::make('file_name')
                            ->label('Nombre del Archivo')
                            ->disabled(),
                    ])->columns(3),
            ]);
    }
}
