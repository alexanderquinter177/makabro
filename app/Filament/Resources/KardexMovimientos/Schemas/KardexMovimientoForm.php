<?php

namespace App\Filament\Resources\KardexMovimientos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;
use App\Models\Auth\User;

class KardexMovimientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalle del Movimiento de Kardex')
                    ->schema([
                        Select::make('sede_id')
                            ->label('Sede')
                            ->options(Sede::pluck('nombre', 'id'))
                            ->disabled(),

                        Select::make('producto_id')
                            ->label('Producto')
                            ->options(Producto::pluck('nombre', 'id'))
                            ->disabled(),

                        Select::make('tipo_movimiento')
                            ->label('Tipo de Movimiento')
                            ->options([
                                'entrada_compra' => 'Entrada por Compra',
                                'salida_venta' => 'Salida por Venta',
                                'ajuste_entrada' => 'Ajuste de Entrada',
                                'ajuste_salida' => 'Ajuste de Salida',
                                'merma_novedad' => 'Merma / Novedad',
                            ])
                            ->disabled(),

                        TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->disabled(),

                        TextInput::make('saldo_despues')
                            ->label('Saldo Después')
                            ->numeric()
                            ->disabled(),

                        Select::make('created_by')
                            ->label('Causado por')
                            ->options(User::pluck('name', 'id'))
                            ->disabled(),

                        TextInput::make('documento_origen_type')
                            ->label('Tipo Documento Origen')
                            ->disabled(),

                        TextInput::make('documento_origen_id')
                            ->label('ID Documento Origen')
                            ->disabled(),

                        DateTimePicker::make('created_at')
                            ->label('Fecha y Hora')
                            ->disabled(),

                        Textarea::make('notas')
                            ->label('Notas')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
