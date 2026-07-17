<?php

namespace App\Filament\Resources\Novedades\Schemas;

use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Producto;
use App\Models\Auth\User;

class NovedadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registro de Novedad / Merma')
                    ->schema([
                        // Primera fila: Sede, Usuario, Responsable
                        Grid::make(3)
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

                                Select::make('usuario_id')
                                    ->label('Registrado por')
                                    ->options(User::where('activo', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->disabled(true)
                                    ->dehydrated()
                                    ->default(fn () => auth()->id())
                                    ->placeholder('Seleccione el usuario'),

                                Select::make('responsable_id')
                                    ->label('Empleado Responsable')
                                    ->options(User::pluck('name', 'id'))
                                    ->searchable()
                                    ->placeholder('Seleccione el responsable (si aplica)'),
                            ])
                            ->columnSpanFull(),

                        // Segunda fila: Tipo, Área, Tipo Afectado
                        Grid::make(3)
                            ->schema([
                                Select::make('tipo')
                                    ->label('Tipo de Novedad')
                                    ->options([
                                        'caída/quiebre' => 'Caída / Quiebre',
                                        'quemado' => 'Quemado',
                                        'vencimiento' => 'Vencimiento',
                                        'daño' => 'Daño',
                                        'devolución' => 'Devolución',
                                        'pérdida/robo' => 'Pérdida / Robo',
                                    ])
                                    ->required()
                                    ->placeholder('Seleccione tipo'),

                                Select::make('area')
                                    ->label('Área')
                                    ->options([
                                        'cocina' => 'Cocina',
                                        'barra' => 'Barra',
                                        'administración' => 'Administración',
                                    ])
                                    ->required()
                                    ->placeholder('Seleccione área'),

                                Select::make('tipo_afectado')
                                    ->label('Tipo Afectado')
                                    ->options([
                                        'producto' => 'Producto / Insumo',
                                        'mueble' => 'Mueble / Equipo',
                                    ])
                                    ->placeholder('Seleccione afectado'),
                            ])
                            ->columnSpanFull(),

                        // Tercera fila: Producto, Cantidad, Valor Costo
                        Grid::make(3)
                            ->schema([
                                Select::make('producto_id')
                                    ->label('Producto Relacionado')
                                    ->options(Producto::where('activo', true)->pluck('nombre', 'id'))
                                    ->searchable()
                                    ->placeholder('Seleccione el producto (si aplica)')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $producto = Producto::find($state);
                                            if ($producto) {
                                                $precio = floatval($producto->precio_compra ?? 0);
                                                $cantidad = floatval($get('cantidad') ?? 0);
                                                $costo = round($cantidad * $precio, 2);
                                                $set('valor_costo', $costo);
                                                $set('valor_cobro', $costo);
                                            }
                                        }
                                    }),

                                TextInput::make('cantidad')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->placeholder('0.00')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $productoId = $get('producto_id');
                                        if ($productoId) {
                                            $producto = Producto::find($productoId);
                                            if ($producto) {
                                                $precio = floatval($producto->precio_compra ?? 0);
                                                $cantidad = floatval($state ?? 0);
                                                $costo = round($cantidad * $precio, 2);
                                                $set('valor_costo', $costo);
                                                $set('valor_cobro', $costo);
                                            }
                                        }
                                    }),

                                TextInput::make('valor_costo')
                                    ->label('Valor Costo')
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('0')
                                    ->readOnly()
                                    ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                    ->stripCharacters('.')
                                    ->validationAttribute('Valor Costo'),
                            ])
                            ->columnSpanFull(),

                        // Cuarta fila: Valor Cobro, Estado Cobro
                        Grid::make(2)
                            ->schema([
                                TextInput::make('valor_cobro')
                                    ->label('Valor Cobro')
                                    ->required()
                                    ->prefix('$')
                                    ->placeholder('0')
                                    ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                    ->stripCharacters('.')
                                    ->validationAttribute('Valor Cobro')
                                    ->helperText('Monto a cobrar al empleado (si aplica)'),

                                Select::make('estado_cobro')
                                    ->label('Estado de Cobro')
                                    ->options([
                                        'si' => 'Cobrado',
                                        'no' => 'No aplica',
                                        'pendiente' => 'Pendiente',
                                    ])
                                    ->required()
                                    ->default('pendiente'),
                            ])
                            ->columnSpanFull(),

                        // Quinta fila: Evidencia (ocupa todo el ancho)
                        FileUpload::make('evidencia_imagen')
                            ->label('Evidencia (Imagen)')
                            ->image()
                            ->directory('novedades-evidencias')
                            ->columnSpanFull(),

                        // Sexta fila: Descripción (ocupa todo el ancho)
                        Textarea::make('descripcion')
                            ->label('Descripción de la Novedad')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}