<?php

namespace App\Filament\Resources\Compras\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Catalog\Sede;
use App\Models\Catalog\Proveedor;
use App\Models\Catalog\Producto;
use App\Models\Auth\User;

use Filament\Support\RawJs;

class CompraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ── CABECERA: Información de la Compra ─────────────────────────
                Section::make('Información de la Compra')
                    ->icon('heroicon-o-shopping-cart')
                    ->description('Datos básicos de la factura y proveedor')
                    ->schema([
                        // Sede y proveedor en grid de 2 columnas
                        Grid::make(2)
                            ->schema([
                                Select::make('sede_id')
                                    ->label('Sede')
                                    ->options(Sede::where('activo', true)->pluck('nombre', 'id'))
                                    ->required()
                                    ->validationAttribute('Sede')
                                    ->searchable()
                                    ->placeholder('Seleccione la sede')
                                    ->prefixIcon('heroicon-o-building-office-2')
                                    ->disabled(true)
                                    ->dehydrated()
                                    ->default(fn () => session('sede_id')),

                               Select::make('proveedor_id')
                                ->label('Proveedor')
                                ->options(Proveedor::where('activo', true)->pluck('nombre', 'id'))
                                ->required()
                                ->validationAttribute('Proveedor')
                                ->searchable()
                                ->placeholder('Seleccione el proveedor')
                                ->prefixIcon('heroicon-o-truck')
                                ->createOptionUsing(function (array $data): int {
                                    // Convertir a mayúsculas antes de guardar
                                    return Proveedor::create([
                                        'nombre' => strtoupper($data['nombre']),
                                        'nit' => strtoupper($data['nit']),
                                        'telefono' => $data['telefono'] ?? null,
                                        'email' => strtolower($data['email'] ?? ''), // Email en minúsculas
                                        'activo' => true,
                                    ])->id;
                                })
                                ->createOptionForm([
                                    TextInput::make('nombre')
                                        ->label('Nombre')
                                        ->required()
                                        ->maxLength(255)
                                        ->formatStateUsing(fn ($state) => strtoupper($state)) // Mostrar en mayúsculas
                                        ->afterStateHydrated(fn ($state, $set) => $set('nombre', strtoupper($state))) // Al cargar
                                        ->reactive()
                                        ->afterStateUpdated(fn ($state, $set) => $set('nombre', strtoupper($state))), // Al escribir
                                    TextInput::make('nit')
                                        ->label('NIT')
                                        ->required()
                                        ->maxLength(20)
                                        ->formatStateUsing(fn ($state) => strtoupper($state))
                                        ->afterStateUpdated(fn ($state, $set) => $set('nit', strtoupper($state))),
                                    TextInput::make('telefono')
                                        ->label('Teléfono')
                                        ->maxLength(20),
                                    TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->maxLength(255)
                                        ->formatStateUsing(fn ($state) => strtolower($state))
                                        ->afterStateUpdated(fn ($state, $set) => $set('email', strtolower($state))),
                                ]),
                            ])
                            ->columnSpanFull(),

                        // Tipo + registrado por en grid de 2 columnas
                        Grid::make(2)
                            ->schema([
                                Select::make('tipo_compra')
                                    ->label('Tipo de Compra')
                                    ->options([
                                        'materia_prima'  => '🥩 Materia Prima',
                                        'aseo'           => '🧹 Aseo / Limpieza',
                                        'desechables'    => '🥡 Desechables',
                                        'bebidas'        => '🥤 Bebidas',
                                        'utensilios'     => '🍴 Utensilios',
                                        'equipos'        => '⚙️ Equipos',
                                        'otros'          => '📦 Otros',
                                    ])
                                    ->required()
                                    ->validationAttribute('Tipo de Compra')
                                    ->default('materia_prima')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->helperText('Clasificación de la compra'),

                                Select::make('usuario_id')
                                    ->label('Registrado por')
                                    ->options(User::where('activo', true)->pluck('name', 'id'))
                                    ->required()
                                    ->validationAttribute('Registrado por')
                                    ->searchable()
                                    ->default(fn () => auth()->id())
                                    ->placeholder('Seleccione el usuario')
                                    ->prefixIcon('heroicon-o-user')
                                    ->disabled(true)
                                    ->dehydrated(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // ── FACTURA Y PAGOS ────────────────────────────────────────────
                Section::make('Factura y Pagos')
                    ->icon('heroicon-o-document-text')
                    ->description('Datos del comprobante y método de pago')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('numero_factura')
                                    ->label('Número de Factura')
                                    ->required()
                                    ->validationAttribute('Número de Factura')
                                    ->unique(
                                        table: 'compras',
                                        column: 'numero_factura',
                                        ignoreRecord: true,
                                        modifyRuleUsing: function ($rule, $get) {
                                            return $rule->where('proveedor_id', $get('proveedor_id'));
                                        }
                                    )
                                    ->maxLength(255)
                                    ->placeholder('FAC-12345')
                                    ->prefixIcon('heroicon-o-hashtag'),

                                DatePicker::make('fecha_factura')
                                    ->label('Fecha de Factura')
                                    ->required()
                                    ->validationAttribute('Fecha de Factura')
                                    ->default(now())
                                    ->displayFormat('d/m/Y')
                                    ->prefixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_registro')
                                    ->label('Fecha de Registro')
                                    ->required()
                                    ->validationAttribute('Fecha de Registro')
                                    ->default(now())
                                    ->displayFormat('d/m/Y')
                                    ->prefixIcon('heroicon-o-clock')
                                    ->disabled(true)
                                    ->dehydrated(),
                            ])
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Select::make('forma_pago')
                                    ->label('Forma de Pago')
                                    ->options([
                                        'efectivo'       => '💵 Efectivo',
                                        'transferencia'  => '🏦 Transferencia',
                                        'credito'        => '💳 Crédito',
                                        'cheque'         => '📝 Cheque',
                                        'contraentrega'  => '📦 Contra Entrega',
                                    ])
                                    ->required()
                                    ->validationAttribute('Forma de Pago')
                                    ->default('credito')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->helperText('Método de pago acordado'),

                                TextInput::make('recibido_por')
                                    ->label('Recibido por')
                                    ->maxLength(255)
                                    ->placeholder('Nombre de quien recibe')
                                    ->prefixIcon('heroicon-o-user-group')
                                    ->helperText('Persona que recibe la mercancía'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // ── PRODUCTOS ─────────────────────────────────────────────────
                Section::make('Productos de la Compra')
                    ->icon('heroicon-o-list-bullet')
                    ->description('Agregue los productos que está comprando')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Grid::make(12)
                                    ->schema([
                                        Select::make('producto_id')
                                            ->label('Producto')
                                            ->options(Producto::where('activo', true)->pluck('nombre', 'id'))
                                            ->required()
                                            ->validationAttribute('Producto')
                                            ->searchable()
                                            ->placeholder('Seleccione el producto')
                                            ->prefixIcon('heroicon-o-cube')
                                            ->columnSpan(4)
                                            ->reactive()
                                            ->createOptionForm([
                                                TextInput::make('nombre')->label('Nombre')->required()->validationAttribute('Nombre'),
                                                Select::make('unidad_compra_id')
                                                    ->label('Unidad de Compra')
                                                    ->relationship('unidadCompra', 'nombre')
                                                    ->required()
                                                    ->validationAttribute('Unidad de Compra'),
                                                TextInput::make('precio_compra')
                                                    ->label('Precio Compra')
                                                    ->numeric()
                                                    ->required()
                                                    ->validationAttribute('Precio Compra'),
                                            ])
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                if ($state) {
                                                    $producto = Producto::find($state);
                                                    if ($producto) {
                                                        $precio = $producto->precio_compra ?? 0;
                                                        $set('precio_unitario', $precio);
                                                        $set('unidad_compra', $producto->unidadCompra?->abreviatura ?? '---');
                                                        
                                                        $cantidad = floatval($get('cantidad') ?? 0);
                                                        $set('total', round($cantidad * $precio, 2));
                                                    }
                                                }
                                            }),

                                        Select::make('unidad_compra')
                                            ->label('U.M.')
                                            ->placeholder('Seleccionar')
                                            ->options([
                                                'kg' => 'Kilogramo (kg)',
                                                'gr' => 'Gramo (gr)',
                                                'lt' => 'Litro (lt)',
                                                'ml' => 'Mililitro (ml)',
                                                'und' => 'Unidad (und)',
                                            ])
                                            ->required()
                                            ->validationAttribute('U.M.')
                                            ->searchable()
                                            ->columnSpan(2)
                                            ->reactive()
                                            ->afterStateHydrated(function ($set, $get, $state) {
                                                $productoId = $get('producto_id');
                                                if ($productoId && !$state) {
                                                    $producto = Producto::find($productoId);
                                                    if ($producto) {
                                                        $set('unidad_compra', $producto->unidadCompra?->abreviatura ?? '---');
                                                    }
                                                }
                                            })
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                 $productoId = $get('producto_id');
                                                 if ($productoId && $state) {
                                                     $producto = Producto::find($productoId);
                                                     if ($producto) {
                                                         $baseUnit = strtolower($producto->unidadCompra?->abreviatura ?? 'und');
                                                         $selectedUnit = strtolower($state);
                                                         
                                                         $factor = 1;
                                                         $compatible = true;
                                                         
                                                         if ($baseUnit === 'gr') {
                                                             if ($selectedUnit === 'kg') {
                                                                 $factor = 1000;
                                                             } elseif ($selectedUnit === 'gr') {
                                                                 $factor = 1;
                                                             } else {
                                                                 $compatible = false;
                                                             }
                                                         } elseif ($baseUnit === 'ml') {
                                                             if ($selectedUnit === 'lt') {
                                                                 $factor = 1000;
                                                             } elseif ($selectedUnit === 'ml') {
                                                                 $factor = 1;
                                                             } else {
                                                                 $compatible = false;
                                                             }
                                                         } else {
                                                             if ($selectedUnit !== $baseUnit) {
                                                                 $compatible = false;
                                                             }
                                                         }
                                                         
                                                         if (!$compatible) {
                                                             \Filament\Notifications\Notification::make()
                                                                 ->title('Unidad incompatible')
                                                                 ->body("La unidad '{$selectedUnit}' no es compatible con la unidad base del producto ('{$baseUnit}').")
                                                                 ->warning()
                                                                 ->send();
                                                             
                                                             $set('unidad_compra', $baseUnit);
                                                             return;
                                                         }
                                                         
                                                         $precioBase = $producto->precio_compra ?? 0;
                                                         $nuevoPrecio = $precioBase * $factor;
                                                         $set('precio_unitario', $nuevoPrecio);
                                                         
                                                         $cantidad = floatval($get('cantidad') ?? 0);
                                                         $set('total', round($cantidad * $nuevoPrecio, 2));
                                                     }
                                                 }
                                             }),

                                        TextInput::make('cantidad')
                                            ->label('Cantidad')
                                            ->numeric()
                                            ->required()
                                            ->validationAttribute('Cantidad')
                                            ->minValue(0.01)
                                            ->step(0.01)
                                            ->live(onBlur: true)
                                            ->placeholder('0')
                                            ->prefixIcon('heroicon-o-calculator')
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                 $precioRaw = $get('precio_unitario') ?? 0;
                                                 $precio = floatval(str_replace('.', '', $precioRaw));
                                                 $cantidad = floatval($state);
                                                 $set('total', round($cantidad * $precio, 2));
                                             }),

                                        TextInput::make('precio_unitario')
                                            ->label('Precio Unit.')
                                            ->required()
                                            ->validationAttribute('Precio Unitario')
                                            ->live(onBlur: true)
                                            ->prefix('$')
                                            ->placeholder('0')
                                            ->columnSpan(2)
                                            ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                            ->stripCharacters('.')
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                 $cantidadRaw = $get('cantidad') ?? 0;
                                                 $cantidad = floatval($cantidadRaw);
                                                 $precio = floatval(str_replace('.', '', $state));
                                                 $set('total', round($cantidad * $precio, 2));
                                             }),

                                        TextInput::make('total')
                                            ->label('Total')
                                            ->required()
                                            ->validationAttribute('Total')
                                            ->readOnly()
                                            ->prefix('$')
                                            ->placeholder('0')
                                            ->columnSpan(2)
                                            ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                            ->stripCharacters('.'),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->createItemButtonLabel('➕ Agregar producto')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 rounded-xl'])
                            ->live()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                 $items = $get('items') ?? [];
                                 $subtotal = 0;
                                 foreach ($items as $item) {
                                     $itemTotal = floatval(str_replace('.', '', $item['total'] ?? 0));
                                     $subtotal += $itemTotal;
                                 }
                                 $set('subtotal', $subtotal);
                                 $set('total', $subtotal);
                             }),
                    ])
                    ->columnSpanFull(),

                // ── TOTALES ─────────────────────────────────
                Grid::make(3)
                    ->schema([
                        Placeholder::make('spacer_left')
                            ->label('')
                            ->hiddenLabel()
                            ->columnSpan(1),

                        Section::make('Resumen de Totales')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                TextInput::make('subtotal')
                                    ->hidden(),

                                TextInput::make('total')
                                    ->label('Total a Pagar')
                                    ->required()
                                    ->default(0)
                                    ->prefix('$ ')
                                    ->placeholder('0')
                                    ->readOnly()
                                    ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                    ->stripCharacters('.')
                                    ->validationAttribute('Total a Pagar')
                                    ->extraAttributes([
                                        'class' => 'font-bold text-xl text-primary-600 text-center',
                                    ]),
                            ])
                            ->columnSpan(1),

                        Placeholder::make('spacer_right')
                            ->label('')
                            ->hiddenLabel()
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),

                // ── OBSERVACIONES ──────────────────────────────────────────────
                Section::make('Observaciones')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->description('Notas adicionales sobre la compra')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Textarea::make('notas')
                            ->label('Notas / Observaciones')
                            ->maxLength(65535)
                            ->placeholder('Ingrese cualquier observación relevante...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none']),

                        Textarea::make('observaciones_internas')
                            ->label('Observaciones Internas')
                            ->maxLength(65535)
                            ->placeholder('Notas internas (solo para administradores)...')
                            ->rows(2)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none'])
                            ->hidden(fn () => !auth()->user()?->hasRole('super_admin') && !auth()->user()?->hasRole('admin')),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}