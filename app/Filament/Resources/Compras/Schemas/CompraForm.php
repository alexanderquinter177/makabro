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

                        // Tipo + registrado por + estado en grid de 3 columnas
                        Grid::make(3)
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

                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'borrador'  => '📝 Borrador',
                                        'pendiente' => '⏳ Pendiente de Aprobación'
                                    ])
                                    ->required()
                                    ->default('borrador')
                                    ->disabled(fn ($record) => $record !== null && $record->status !== 'borrador')
                                    ->dehydrated()
                                    ->prefixIcon('heroicon-o-flag')
                                    ->helperText('Estado actual de la compra'),
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

                        Grid::make(3)
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

                                FileUpload::make('imagen_factura')
                                    ->label('Documento / Soporte de Factura')
                                    ->disk('public')
                                    ->directory('compras/facturas')
                                    ->maxSize(10240)
                                    ->placeholder('Suba la factura o documento soporte (cualquier formato)')
                                    ->downloadable()
                                    ->openable()
                                    ->visible(fn ($record) => $record === null || $record->status === 'borrador'),

                                Placeholder::make('ver_factura')
                                    ->label('Documento Soporte (Factura)')
                                    ->content(fn ($record) => $record?->imagen_factura ? new \Illuminate\Support\HtmlString(
                                        '<a href="' . asset('storage/' . $record->imagen_factura) . '" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition shadow-sm" style="background-color: #4f46e5; color: #ffffff; padding: 8px 16px; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                            <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Ver Factura / Soporte
                                        </a>'
                                    ) : 'No se cargó ningún documento soporte para esta compra.')
                                    ->visible(fn ($record) => $record !== null && $record->status !== 'borrador'),

                               
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
                                            ->options(Producto::pluck('nombre', 'id'))
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
                                                        $set('precio_unitario', number_format((float) $precio, 0, ',', '.'));
                                                        $set('unidad_compra', $producto->unidadCompra?->abreviatura ?? '---');
                                                        
                                                        $cantidad = floatval($get('cantidad') ?? 0);
                                                        $totalCalc = round($cantidad * $precio, 0);
                                                        $set('total', number_format($totalCalc, 0, ',', '.'));

                                                        $items = $get('../../items') ?? [];
                                                        $grandTotal = 0;
                                                        foreach ($items as $item) {
                                                            $grandTotal += floatval(str_replace('.', '', $item['total'] ?? 0));
                                                        }
                                                        $set('../../subtotal', number_format($grandTotal, 0, ',', '.'));
                                                        $set('../../total', number_format($grandTotal, 0, ',', '.'));
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
                                            ->afterStateHydrated(function ($set, $get, $state, string $operation) {
                                                if ($operation === 'edit') {
                                                    return;
                                                }
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
                                                         $set('precio_unitario', number_format((float) $nuevoPrecio, 0, ',', '.'));
                                                         
                                                         $cantidad = floatval($get('cantidad') ?? 0);
                                                         $totalCalc = round($cantidad * $nuevoPrecio, 0);
                                                         $set('total', number_format($totalCalc, 0, ',', '.'));

                                                         $items = $get('../../items') ?? [];
                                                         $grandTotal = 0;
                                                         foreach ($items as $item) {
                                                             $grandTotal += floatval(str_replace('.', '', $item['total'] ?? 0));
                                                         }
                                                         $set('../../subtotal', number_format($grandTotal, 0, ',', '.'));
                                                         $set('../../total', number_format($grandTotal, 0, ',', '.'));
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
                                            ->live(debounce: 250)
                                            ->placeholder('0')
                                            ->prefixIcon('heroicon-o-calculator')
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                 $precioRaw = $get('precio_unitario') ?? 0;
                                                 $precio = floatval(str_replace('.', '', $precioRaw));
                                                 $cantidad = floatval($state);
                                                 $totalCalc = round($cantidad * $precio, 0);
                                                 $set('total', number_format($totalCalc, 0, ',', '.'));

                                                 $items = $get('../../items') ?? [];
                                                 $grandTotal = 0;
                                                 foreach ($items as $item) {
                                                     $grandTotal += floatval(str_replace('.', '', $item['total'] ?? 0));
                                                 }
                                                 $set('../../subtotal', number_format($grandTotal, 0, ',', '.'));
                                                 $set('../../total', number_format($grandTotal, 0, ',', '.'));
                                             }),

                                        TextInput::make('precio_unitario')
                                            ->label('Precio Unit.')
                                            ->required()
                                            ->validationAttribute('Precio Unitario')
                                            ->live(debounce: 250)
                                            ->prefix('$')
                                            ->placeholder('0')
                                            ->columnSpan(2)
                                            ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                            ->dehydrateStateUsing(fn ($state) => $state !== null ? str_replace('.', '', $state) : null)
                                            ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) str_replace('.', '', $state), 0, ',', '.') : '')
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                 $cantidadRaw = $get('cantidad') ?? 0;
                                                 $cantidad = floatval($cantidadRaw);
                                                 $precio = floatval(str_replace('.', '', $state));
                                                 $totalCalc = round($cantidad * $precio, 0);
                                                 $set('total', number_format($totalCalc, 0, ',', '.'));

                                                 $items = $get('../../items') ?? [];
                                                 $grandTotal = 0;
                                                 foreach ($items as $item) {
                                                     $grandTotal += floatval(str_replace('.', '', $item['total'] ?? 0));
                                                 }
                                                 $set('../../subtotal', number_format($grandTotal, 0, ',', '.'));
                                                 $set('../../total', number_format($grandTotal, 0, ',', '.'));
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
                                            ->dehydrateStateUsing(fn ($state) => $state !== null ? str_replace('.', '', $state) : null)
                                            ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) str_replace('.', '', $state), 0, ',', '.') : ''),
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
                                 $set('subtotal', number_format($subtotal, 0, ',', '.'));
                                 $set('total', number_format($subtotal, 0, ',', '.'));
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
                                    ->dehydrateStateUsing(fn ($state) => $state !== null ? str_replace('.', '', $state) : null)
                                    ->validationAttribute('Total a Pagar')
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : '')
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