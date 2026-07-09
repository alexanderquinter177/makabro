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
                                    ->searchable()
                                    ->placeholder('Seleccione el proveedor')
                                    ->prefixIcon('heroicon-o-truck')
                                    ->createOptionForm([
                                        TextInput::make('nombre')->label('Nombre')->required(),
                                        TextInput::make('nit')->label('NIT')->required(),
                                        TextInput::make('telefono')->label('Teléfono'),
                                        TextInput::make('email')->label('Email')->email(),
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
                                    ->default('materia_prima')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->helperText('Clasificación de la compra'),

                                Select::make('usuario_id')
                                    ->label('Registrado por')
                                    ->options(User::where('activo', true)->pluck('name', 'id'))
                                    ->required()
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
                                    ->maxLength(255)
                                    ->placeholder('FAC-12345')
                                    ->prefixIcon('heroicon-o-hashtag'),

                                DatePicker::make('fecha_factura')
                                    ->label('Fecha de Factura')
                                    ->required()
                                    ->default(now())
                                    ->displayFormat('d/m/Y')
                                    ->prefixIcon('heroicon-o-calendar'),

                                DatePicker::make('fecha_registro')
                                    ->label('Fecha de Registro')
                                    ->required()
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
                                Grid::make(5)
                                    ->schema([
                                        Select::make('producto_id')
                                            ->label('Producto')
                                            ->options(Producto::where('activo', true)->pluck('nombre', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->placeholder('Seleccione el producto')
                                            ->prefixIcon('heroicon-o-cube')
                                            ->columnSpan(2)
                                            ->createOptionForm([
                                                TextInput::make('nombre')->label('Nombre')->required(),
                                                Select::make('unidad_medida_id')
                                                    ->label('Unidad')
                                                    ->relationship('unidadMedida', 'nombre')
                                                    ->required(),
                                                TextInput::make('precio_compra')
                                                    ->label('Precio Compra')
                                                    ->numeric()
                                                    ->required(),
                                            ]),

                                        TextInput::make('cantidad')
                                            ->label('Cantidad')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0.01)
                                            ->step(0.01)
                                            ->live(onBlur: true)
                                            ->placeholder('0')
                                            ->prefixIcon('heroicon-o-calculator')
                                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                                $set('total', round(floatval($state) * floatval($get('precio_unitario') ?? 0), 2))
                                            ),

                                        TextInput::make('precio_unitario')
                                            ->label('Precio Unit.')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->live(onBlur: true)
                                            ->prefix('$')
                                            ->placeholder('0.00')
                                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                                $set('total', round(floatval($state) * floatval($get('cantidad') ?? 0), 2))
                                            ),

                                        TextInput::make('total')
                                            ->label('Total')
                                            ->numeric()
                                            ->required()
                                            ->readOnly()
                                            ->prefix('$')
                                            ->placeholder('0.00'),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->createItemButtonLabel('➕ Agregar producto')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 rounded-xl']),
                    ])
                    ->columnSpanFull(),

                // ── TOTALES + ESTADO ─────────────────────────────────
                Grid::make(2)
                    ->schema([
                        Section::make('Resumen de Totales')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$ ')
                                    ->readOnly()
                                    ->placeholder('0.00'),

                                TextInput::make('iva')
                                    ->label('IVA (19%)')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$ ')
                                    ->placeholder('0.00')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $set('total', round(floatval($get('subtotal') ?? 0) + floatval($state ?? 0), 2));
                                    }),

                                TextInput::make('total')
                                    ->label('Total a Pagar')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->prefix('$ ')
                                    ->placeholder('0.00')
                                    ->readOnly()
                                    ->extraAttributes(['class' => 'font-bold text-lg text-primary-600']),
                            ]),

                        Section::make('Estado')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Toggle::make('registro_tardio')
                                    ->label('Registro Tardío')
                                    ->default(false)
                                    ->onColor('warning')
                                    ->offColor('gray')
                                    ->helperText('Se registró después de la fecha'),

                                Toggle::make('recibido')
                                    ->label('Mercancía Recibida')
                                    ->default(false)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('La mercancía fue recibida'),

                                Toggle::make('pagado')
                                    ->label('Factura Pagada')
                                    ->default(false)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->helperText('La factura fue pagada'),
                            ]),
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