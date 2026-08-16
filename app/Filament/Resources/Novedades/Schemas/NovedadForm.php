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

                                TextInput::make('responsable_nombre')
                                    ->label('Empleado Responsable')
                                    ->maxLength(255)
                                    ->placeholder('Escriba el nombre del empleado')
                                    ->dehydrateStateUsing(fn (?string $state): ?string => $state ? mb_strtoupper($state, 'UTF-8') : null)
                                    ->formatStateUsing(fn (?string $state): ?string => $state ? mb_strtoupper($state, 'UTF-8') : null),
                            ])
                            ->columnSpanFull(),

                        // Segunda fila: Tipo, Área, Tipo Afectado
                        Grid::make(3)
                            ->schema([
                                Select::make('tipo')
                                ->label('Tipo de Merma')
                                ->options([                                    
                                    '⏰ Caducidad y Frescura' => [
                                        'vencimiento' => 'Vencimiento/Caducado',
                                        'producto_perecedero' => 'Producto Perecedero Dañado',
                                        'lacteo_vencido' => 'Lácteo Vencido',
                                    ],
                                    '🥩 Calidad del Producto' => [
                                        'carne_mala' => 'Carne en Mal Estado',
                                        'pescado_malo' => 'Pescado/Mariscos en Mal Estado',
                                        'verdura_mala' => 'Verdura/Fruta en Mal Estado',
                                        'huevo_malo' => 'Huevos en Mal Estado',
                                    ],
                                    '🔧 Manipulación y Almacenamiento' => [
                                        'caida' => 'Caída/Quiebre',
                                        'derrame' => 'Derrame/Fuga',
                                        'quemado' => 'Quemado/Calcinado',
                                        'mal_almacenamiento' => 'Mal Almacenamiento',
                                        'cadena_frio' => 'Rotura de Cadena de Frío',
                                    ],
                                    '🍽️ Servicio y Consumo' => [
                                        'devolucion_comensal' => 'Devolución del Comensal',
                                        'sobrante_cocina' => 'Sobrante de Cocina',
                                    ],
                                    '📦 Devoluciones y Control' => [
                                        'devolucion_proveedor' => 'Devolución a Proveedor',
                                        'rechazo_calidad' => 'Rechazo por Control de Calidad',
                                        'rotura_inventario' => 'Rotura de Inventario',
                                        'error_conteo' => 'Error de Conteo',
                                    ],
                                    '🔒 Seguridad' => [
                                        'robo' => 'Robo/Hurto',
                                        'perdida' => 'Pérdida No Justificada',
                                    ],                                    
                                    '📝 Otros' => [
                                        'Cortesia' => 'Cortesia',
                                        'otro' => 'Otro (especificar en notas)',
                                    ],                                    
                                ])
                                ->searchable()
                                ->required()
                                ->placeholder('Seleccione el tipo de merma en el restaurante')
                                ->helperText('Mermas relacionadas con la operación del restaurante y cocina'),

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
                                    ->searchable()
                                    ->placeholder('Seleccione el producto (si aplica)')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $producto = Producto::withoutGlobalScope('sede')->find($state);
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
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $productoId = $get('producto_id');
                                        if ($productoId) {
                                            $producto = Producto::withoutGlobalScope('sede')->find($productoId);
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
                                    ->dehydrateStateUsing(fn ($state) => $state !== null ? str_replace('.', '', $state) : null)
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : '')
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
                                    ->dehydrateStateUsing(fn ($state) => $state !== null ? str_replace('.', '', $state) : null)
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : '')
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
                            ->disk('public')
                            ->directory('novedades/evidencias')
                            ->extraInputAttributes([
                                'capture' => 'environment',
                            ])
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