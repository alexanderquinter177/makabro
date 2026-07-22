<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Models\Catalog\Categoria;
use App\Models\Catalog\UnidadMedida;
use App\Models\Catalog\Producto;
use Illuminate\Support\HtmlString;
use Filament\Support\RawJs;

class ProductoForm
{
    public static function configure(Schema $schema, ?string $forceTipo = null): Schema
    {
        return $schema
            ->components([
                // ── SECCIÓN PRINCIPAL: Datos del Producto ─────────────────────────
                Section::make('INFORMACIÓN GENERAL DEL PRODUCTO')
                    ->icon('heroicon-o-shopping-bag')
                    ->description('Registre los detalles básicos, clasificación y precios del producto')
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('NOMBRE DEL PRODUCTO')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('EJ: AURA VERDE')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->columnSpan(['default' => 1, 'sm' => 2])
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('nombre', strtoupper($state))),

                                TextInput::make('codigo')
                                    ->label('CÓDIGO / SKU')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('AUTO-GENERADO')
                                    ->prefixIcon('heroicon-o-hashtag')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(1)
                                    ->default(function ($livewire) use ($forceTipo) {
                                        try {
                                            if ($livewire instanceof \Filament\Resources\Pages\CreateRecord) {
                                                $tipo = $livewire->form->getState()['tipo'] ?? $forceTipo;
                                                $categoriaId = $livewire->form->getState()['categoria_id'] ?? null;
                                                if ($tipo && $categoriaId) {
                                                    return Producto::generarCodigo($tipo, $categoriaId);
                                                }
                                            }
                                            return null;
                                        } catch (\Exception $e) {
                                            return null;
                                        }
                                    })
                                    ->afterStateHydrated(function ($state, $set, $get) use ($forceTipo) {
                                        $tipo = $get('tipo') ?: $forceTipo;
                                        $categoriaId = $get('categoria_id');
                                        if ($tipo && $categoriaId && empty($state)) {
                                            try {
                                                $set('codigo', Producto::generarCodigo($tipo, $categoriaId));
                                            } catch (\Exception $e) {
                                                // No mostrar error
                                            }
                                        }
                                    }),
                            ]),

                        Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])
                            ->schema([
                                Select::make('tipo')
                                    ->label('TIPO DE PRODUCTO')
                                    ->options([
                                        'venta'       => '🍽️ VENTA (Plato final)',
                                        'subensamble' => '🔧 SUBENSAMBLE (Receta interna)',
                                        'insumo'      => '📦 INSUMO (Materia prima)',
                                    ])
                                    ->required()
                                    ->default($forceTipo ?? 'insumo')
                                    ->disabled($forceTipo !== null)
                                    ->dehydrated()
                                    ->prefixIcon('heroicon-o-cube')
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $categoriaId = $get('categoria_id');
                                        if ($state && $categoriaId) {
                                            try {
                                                $set('codigo', Producto::generarCodigo($state, $categoriaId));
                                            } catch (\Exception $e) {
                                                // No mostrar error
                                            }
                                        }
                                    }),

                                Select::make('categoria_id')
                                    ->label('CATEGORÍA')
                                    ->options(Categoria::activas()->pluck('nombre', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Seleccione categoría')
                                    ->prefixIcon('heroicon-o-folder')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $tipo = $get('tipo');
                                        if ($tipo && $state) {
                                            try {
                                                $set('codigo', Producto::generarCodigo($tipo, $state));
                                            } catch (\Exception $e) {
                                                // No mostrar error
                                            }
                                        }
                                    }),

                                Select::make('unidad_compra_id')
                                    ->label('UNIDAD DE COMPRA')
                                    ->options(UnidadMedida::activos()->pluck('nombre', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Seleccione unidad')
                                    ->prefixIcon('heroicon-o-scale'),
                            ]),

                        Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])
                            ->schema([
                                TextInput::make('precio_compra')
                                    ->label(fn ($get, $record) => ($get('tipo') ?? $record?->tipo ?? $forceTipo) === 'insumo' ? 'PRECIO DE COMPRA' : 'PRECIO DE VENTA ACTUAL')
                                    ->default(0)
                                    ->prefix('$')
                                    ->placeholder('0')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                                    ->formatStateUsing(fn ($state) => $state !== null ? number_format((float) $state, 0, ',', '.') : '')
                                    ->dehydrateStateUsing(function ($state) {
                                        if ($state === null || $state === '') return 0;
                                        if (is_numeric($state)) return (float) $state;
                                        $clean = str_replace(['$', ' '], '', (string)$state);
                                        if (str_contains($clean, ',') && str_contains($clean, '.')) {
                                            $clean = str_replace('.', '', $clean);
                                            $clean = str_replace(',', '.', $clean);
                                        } else {
                                            $clean = str_replace('.', '', $clean);
                                            $clean = str_replace(',', '.', $clean);
                                        }
                                        return floatval($clean);
                                    })
                                    ->live(onBlur: true)
                                    ->visible(fn (callable $get, $record) => in_array($get('tipo') ?? $record?->tipo ?? $forceTipo, ['venta', 'insumo'])),

                                TextInput::make('proveedor_habitual')
                                    ->label('PROVEEDOR HABITUAL')
                                    ->maxLength(255)
                                    ->placeholder('EJ: DISTRIBUIDORA CENTRAL')
                                    ->prefixIcon('heroicon-o-truck')
                                    ->formatStateUsing(fn ($state) => strtoupper($state))
                                    ->afterStateUpdated(fn ($set, $state) => $set('proveedor_habitual', strtoupper($state)))
                                    ->visible(fn (callable $get, $record) => ($get('tipo') ?? $record?->tipo ?? $forceTipo) === 'insumo'),

                                Toggle::make('activo')
                                    ->label('PRODUCTO ACTIVO')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->helperText('Habilita o deshabilita en el sistema'),
                            ]),



                        Textarea::make('notas')
                            ->label('NOTAS Y OBSERVACIONES')
                            ->maxLength(65535)
                            ->placeholder('INFORMACIÓN ADICIONAL O DETALLES DEL PRODUCTO...')
                            ->rows(2)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'resize-none'])
                            ->formatStateUsing(fn ($state) => strtoupper($state))
                            ->afterStateUpdated(fn ($set, $state) => $set('notas', strtoupper($state))),
                    ])
                    ->columnSpanFull(),

                // ── SECCIÓN: Receta / Componentes (BOM) ───────────────────────────
                Section::make('RECETA / INGREDIENTES (BOM)')
                    ->icon('heroicon-o-beaker')
                    ->description('Defina la composición de ingredientes y cantidades para elaborar este producto')
                    ->visible(fn (callable $get, $record) => in_array($get('tipo') ?? $record?->tipo ?? $forceTipo, ['venta', 'subensamble']))
                    ->schema([
                        Repeater::make('componentes')
                            ->relationship('componentes')
                            ->live()
                            ->schema([
                                Grid::make(12)
                                    ->schema([
                                        Select::make('producto_hijo_id')
                                            ->label('INGREDIENTE / INSUMO')
                                            ->options(Producto::where('tipo', '!=', 'venta')
                                                ->where('activo', true)
                                                ->orderBy('nombre')
                                                ->pluck('nombre', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->placeholder('Seleccione el ingrediente')
                                            ->prefixIcon('heroicon-o-cube')
                                            ->columnSpan(['default' => 12, 'md' => 4])
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                if ($state) {
                                                    $producto = Producto::with('unidadCompra')->find($state);
                                                    if ($producto) {
                                                        $unidad = $producto->unidadCompra?->abreviatura ?? 'UND';
                                                        $set('unidad_medida', $unidad);
                                                        
                                                        $costoU = floatval($producto->getCostoUnitario());
                                                        $set('precio_unitario_ingrediente', number_format($costoU, 2, ',', '.'));

                                                        $cantidad = floatval($get('cantidad') ?? 0);
                                                        if ($cantidad > 0) {
                                                            $total = $costoU * $cantidad;
                                                            $set('costo_parcial', '$ ' . number_format($total, 0, ',', '.'));
                                                        }
                                                    }
                                                }
                                            }),

                                        TextInput::make('unidad_medida')
                                            ->label('UNIDAD')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->default('UND')
                                            ->columnSpan(['default' => 4, 'md' => 2])
                                            ->extraAttributes(['class' => 'bg-gray-100 dark:bg-gray-800 text-center'])
                                            ->afterStateHydrated(function ($set, $get, $state) {
                                                $productoHijoId = $get('producto_hijo_id');
                                                if ($productoHijoId) {
                                                    $producto = Producto::find($productoHijoId);
                                                    if ($producto) {
                                                        $set('unidad_medida', $producto->unidadCompra?->abreviatura ?? 'UND');
                                                    }
                                                }
                                            }),

                                        TextInput::make('precio_unitario_ingrediente')
                                            ->label('COSTO UNITARIO')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->default(0)
                                            ->columnSpan(['default' => 8, 'md' => 2])
                                            ->extraAttributes(['class' => 'bg-gray-100 dark:bg-gray-800'])
                                            ->afterStateHydrated(function ($set, $get, $state) {
                                                $productoHijoId = $get('producto_hijo_id');
                                                if ($productoHijoId) {
                                                    $producto = Producto::find($productoHijoId);
                                                    if ($producto) {
                                                        $costoU = floatval($producto->getCostoUnitario());
                                                        $set('precio_unitario_ingrediente', number_format($costoU, 2, ',', '.'));
                                                    }
                                                }
                                            }),

                                        TextInput::make('cantidad')
                                            ->label('CANTIDAD')
                                            ->numeric()
                                            ->required()
                                            ->minValue(0.001)
                                            ->step(0.001)
                                            ->placeholder('0.000')
                                            ->prefixIcon('heroicon-o-calculator')
                                            ->columnSpan(['default' => 6, 'md' => 2])
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $precioRaw = $get('precio_unitario_ingrediente') ?? '0';
                                                $precioClean = str_replace(['.', ','], ['', '.'], $precioRaw);
                                                $precio = floatval($precioClean);
                                                $cantidad = floatval($state) ?? 0;
                                                $total = $precio * $cantidad;
                                                $set('costo_parcial', '$ ' . number_format($total, 0, ',', '.'));
                                            }),

                                        TextInput::make('costo_parcial')
                                            ->label('COSTO PRODUCCIÓN')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->default(0)
                                            ->columnSpan(['default' => 6, 'md' => 2])
                                            ->extraAttributes(['class' => 'bg-gray-100 dark:bg-gray-800 font-bold'])
                                            ->afterStateHydrated(function ($set, $get, $state) {
                                                $productoHijoId = $get('producto_hijo_id');
                                                if ($productoHijoId) {
                                                    $producto = Producto::find($productoHijoId);
                                                    if ($producto) {
                                                        $costoU = floatval($producto->getCostoUnitario());
                                                        $cantidad = floatval($get('cantidad') ?? 0);
                                                        $total = $costoU * $cantidad;
                                                        $set('costo_parcial', '$ ' . number_format($total, 0, ',', '.'));
                                                    }
                                                }
                                            }),

                                        TextInput::make('nota')
                                            ->label('NOTAS / INSTRUCCIÓN')
                                            ->maxLength(255)
                                            ->placeholder('EJ: SIN SAL, PICADO EN CUBOS, OPCIONAL...')
                                            ->prefixIcon('heroicon-o-document-text')
                                            ->columnSpan(12)
                                            ->formatStateUsing(fn ($state) => strtoupper($state))
                                            ->afterStateUpdated(fn ($set, $state) => $set('nota', strtoupper($state))),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->createItemButtonLabel('➕ AGREGAR INGREDIENTE')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 rounded-xl p-3']),
                    ])
                    ->columnSpanFull(),

                // ── SECCIÓN: RESUMEN DE COSTOS ─────────────────────────────────────
                Section::make('RENTABILIDAD Y ESTRUCTURA DE COSTOS')
                    ->icon('heroicon-o-chart-bar')
                    ->description('Análisis financiero detallado de la receta (Food Cost %, margen y utilidad)')
                    ->visible(fn (callable $get, $record) => in_array($get('tipo') ?? $record?->tipo ?? $forceTipo, ['venta', 'subensamble']))
                    ->schema([
                        Placeholder::make('resumen_costos')
                            ->label('')
                            ->content(function ($get, $record) use ($forceTipo) {
                                $tipo = $get('tipo') ?? $record?->tipo ?? $forceTipo;
                                $componentes = $get('componentes') ?? [];
                                $totalCosto = 0;

                                // Calcular total acumulado de la receta e identificar insumo más caro
                                $insumoMasCaroId = null;
                                $maxCostoItem = 0;

                                foreach ($componentes as $componente) {
                                    $productoHijoId = $componente['producto_hijo_id'] ?? null;
                                    $cantidad = floatval($componente['cantidad'] ?? 0);
                                    if ($productoHijoId && $cantidad > 0) {
                                        $producto = Producto::find($productoHijoId);
                                        if ($producto) {
                                            $costoNum = floatval($producto->getCostoUnitario()) * $cantidad;
                                            $totalCosto += $costoNum;

                                            if ($costoNum > $maxCostoItem) {
                                                $maxCostoItem = $costoNum;
                                                $insumoMasCaroId = $productoHijoId;
                                            }
                                        }
                                    }
                                }

                                $rawPrecioVenta = $get('precio_compra') ?? '0';
                                if (is_numeric($rawPrecioVenta)) {
                                    $precioVenta = floatval($rawPrecioVenta);
                                } else {
                                    $cleanP = str_replace(['$', ' '], '', (string)$rawPrecioVenta);
                                    if (str_contains($cleanP, ',') && str_contains($cleanP, '.')) {
                                        $cleanP = str_replace('.', '', $cleanP);
                                        $cleanP = str_replace(',', '.', $cleanP);
                                    } elseif (str_contains($cleanP, ',')) {
                                        $cleanP = str_replace(',', '.', $cleanP);
                                    } else {
                                        $cleanP = str_replace('.', '', $cleanP);
                                    }
                                    $precioVenta = floatval($cleanP);
                                }
                                $porcentajeCosto = $precioVenta > 0 ? ($totalCosto / $precioVenta) * 100 : 0;
                                $beneficio = $precioVenta - $totalCosto;

                                // Configuración de colores y estados de rentabilidad
                                if ($porcentajeCosto > 45) {
                                    $costoColorClass = 'mk-color-rose';
                                    $costoBgClass = 'mk-bg-rose';
                                    $costoBadgeClass = 'mk-badge-rose';
                                    $costoLabel = 'CRÍTICO / ALTO';
                                } elseif ($porcentajeCosto > 35) {
                                    $costoColorClass = 'mk-color-amber';
                                    $costoBgClass = 'mk-bg-amber';
                                    $costoBadgeClass = 'mk-badge-amber';
                                    $costoLabel = 'MODERADO';
                                } else {
                                    $costoColorClass = 'mk-color-emerald';
                                    $costoBgClass = 'mk-bg-emerald';
                                    $costoBadgeClass = 'mk-badge-emerald';
                                    $costoLabel = 'EXCELENTE';
                                }

                                $utilidadColorClass = $beneficio > 0 ? 'mk-color-emerald' : 'mk-color-rose';
                                $utilidadBadgeClass = $beneficio > 0 ? 'mk-badge-emerald' : 'mk-badge-rose';

                                // Renderizar el panel con diseño UI Premium mediante CSS incrustado
                                $html = '
                                <style id="mk-cost-summary-styles">
                                    .mk-cost-container {
                                        display: flex;
                                        flex-direction: column;
                                        gap: 24px;
                                        width: 100%;
                                        font-family: system-ui, -apple-system, sans-serif;
                                    }
                                    .mk-kpi-grid {
                                        display: grid;
                                        grid-template-columns: repeat(1, minmax(0, 1fr));
                                        gap: 20px;
                                        width: 100%;
                                    }
                                    @media (min-width: 640px) {
                                        .mk-kpi-grid {
                                            grid-template-columns: repeat(2, minmax(0, 1fr));
                                        }
                                    }
                                    @media (min-width: 1024px) {
                                        .mk-kpi-grid {
                                            grid-template-columns: repeat(' . ($tipo === 'subensamble' ? '2' : '4') . ', minmax(0, 1fr));
                                        }
                                    }
                                    .mk-kpi-card {
                                        position: relative;
                                        overflow: hidden;
                                        padding: 20px;
                                        background: #ffffff;
                                        border: 1px solid #e5e7eb;
                                        border-radius: 16px;
                                        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: space-between;
                                        min-height: 130px;
                                        box-sizing: border-box;
                                    }
                                    .dark .mk-kpi-card {
                                        background: #18181b;
                                        border-color: #27272a;
                                    }
                                    .mk-card-blue-border { border-left: 5px solid #3b82f6; }
                                    .mk-card-rose-border { border-left: 5px solid #f43f5e; }
                                    .mk-card-emerald-border { border-left: 5px solid #10b981; }
                                    .mk-card-amber-border { border-left: 5px solid #f59e0b; }
                                    
                                    .mk-kpi-header {
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                        width: 100%;
                                    }
                                    .mk-kpi-title {
                                        font-size: 10px;
                                        font-weight: 800;
                                        color: #a1a1aa;
                                        text-transform: uppercase;
                                        letter-spacing: 0.1em;
                                    }
                                    .mk-kpi-value-container {
                                        display: flex;
                                        align-items: baseline;
                                        justify-content: space-between;
                                        margin-top: 16px;
                                        width: 100%;
                                    }
                                    .mk-kpi-value {
                                        font-size: 26px;
                                        font-weight: 900;
                                        color: #18181b;
                                        line-height: 1;
                                    }
                                    .dark .mk-kpi-value {
                                        color: #ffffff;
                                    }
                                    .mk-kpi-label {
                                        font-size: 9px;
                                        font-weight: 900;
                                        padding: 3px 8px;
                                        border-radius: 9999px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.05em;
                                    }
                                    .mk-kpi-sub {
                                        font-size: 9px;
                                        font-weight: 700;
                                        color: #71717a;
                                        margin-top: 6px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.05em;
                                    }
                                    .dark .mk-kpi-sub {
                                        color: #a1a1aa;
                                    }
                                    .mk-progress-bg {
                                        width: 100%;
                                        height: 8px;
                                        background: #f4f4f5;
                                        border-radius: 9999px;
                                        margin-top: 10px;
                                        overflow: hidden;
                                    }
                                    .dark .mk-progress-bg {
                                        background: #27272a;
                                    }
                                    .mk-progress-fill {
                                        height: 100%;
                                        border-radius: 9999px;
                                    }
                                    
                                    /* Colores Generales */
                                    .mk-color-rose { color: #f43f5e !important; }
                                    .mk-bg-rose { background: #f43f5e !important; }
                                    .mk-badge-rose { background: rgba(244,63,94,0.1) !important; color: #f43f5e !important; }
                                    
                                    .mk-color-amber { color: #d97706 !important; }
                                    .mk-bg-amber { background: #f59e0b !important; }
                                    .mk-badge-amber { background: rgba(245,158,11,0.1) !important; color: #d97706 !important; }
                                    
                                    .mk-color-emerald { color: #10b981 !important; }
                                    .mk-bg-emerald { background: #10b981 !important; }
                                    .mk-badge-emerald { background: rgba(16,185,129,0.1) !important; color: #10b981 !important; }
                                    
                                    .mk-icon-wrapper {
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        padding: 6px;
                                        border-radius: 8px;
                                    }
                                    .mk-icon-blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
                                    .mk-icon-rose { background: rgba(244,63,94,0.1); color: #f43f5e; }
                                    .mk-icon-emerald { background: rgba(16,185,129,0.1); color: #10b981; }

                                    /* Tabla */
                                    .mk-table-container {
                                        border: 1px solid #e4e4e7;
                                        border-radius: 16px;
                                        overflow: hidden;
                                        background: #ffffff;
                                        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                                        width: 100%;
                                    }
                                    .dark .mk-table-container {
                                        border-color: #27272a;
                                        background: #18181b;
                                    }
                                    .mk-table-header {
                                        padding: 16px 20px;
                                        background: #fafafa;
                                        border-bottom: 1px solid #e4e4e7;
                                        display: flex;
                                        align-items: center;
                                        justify-content: space-between;
                                    }
                                    .dark .mk-table-header {
                                        background: #202023;
                                        border-color: #27272a;
                                    }
                                    .mk-table-title {
                                        font-size: 11px;
                                        font-weight: 900;
                                        text-transform: uppercase;
                                        color: #27272a;
                                        letter-spacing: 0.05em;
                                        margin: 0;
                                    }
                                    .dark .mk-table-title {
                                        color: #f4f4f5;
                                    }
                                    .mk-table-subtitle {
                                        font-size: 9px;
                                        color: #71717a;
                                        margin: 2px 0 0 0;
                                    }
                                    .mk-table-badge {
                                        font-size: 9px;
                                        font-weight: 800;
                                        padding: 3px 10px;
                                        background: #f4f4f5;
                                        border-radius: 9999px;
                                        color: #71717a;
                                    }
                                    .dark .mk-table-badge {
                                        background: #27272a;
                                        color: #a1a1aa;
                                    }
                                    .mk-table-scroll {
                                        width: 100%;
                                        overflow-x: auto;
                                    }
                                    .mk-table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        text-align: left;
                                        font-size: 11px;
                                    }
                                    .mk-table th {
                                        padding: 12px 20px;
                                        background: #fafafa;
                                        color: #71717a;
                                        font-size: 9px;
                                        font-weight: 800;
                                        text-transform: uppercase;
                                        border-bottom: 1px solid #e4e4e7;
                                        letter-spacing: 0.05em;
                                    }
                                    .dark .mk-table th {
                                        background: #202023;
                                        border-color: #27272a;
                                        color: #a1a1aa;
                                    }
                                    .mk-table td {
                                        padding: 12px 20px;
                                        border-bottom: 1px solid #f4f4f5;
                                        color: #27272a;
                                        vertical-align: middle;
                                    }
                                    .dark .mk-table td {
                                        border-color: #27272a;
                                        color: #d4d4d8;
                                    }
                                    .mk-table tbody tr:hover {
                                        background: #fafafa;
                                    }
                                    .dark .mk-table tbody tr:hover {
                                        background: #202023;
                                    }
                                    .mk-table-tag {
                                        font-size: 8px;
                                        font-weight: 950;
                                        padding: 2px 6px;
                                        background: #fee2e2;
                                        color: #b91c1c;
                                        border-radius: 4px;
                                        text-transform: uppercase;
                                        letter-spacing: 0.05em;
                                        margin-left: 6px;
                                        display: inline-block;
                                    }
                                    .dark .mk-table-tag {
                                        background: #450a0a;
                                        color: #fca5a5;
                                    }
                                    .mk-table-bar-container {
                                        display: flex;
                                        align-items: center;
                                        justify-content: flex-end;
                                        gap: 8px;
                                    }
                                    .mk-table-bar-bg {
                                        width: 60px;
                                        height: 6px;
                                        background: #f4f4f5;
                                        border-radius: 9999px;
                                        overflow: hidden;
                                    }
                                    .dark .mk-table-bar-bg {
                                        background: #27272a;
                                    }
                                    .mk-table-bar-fill {
                                        height: 100%;
                                        border-radius: 9999px;
                                    }
                                    .mk-table tfoot {
                                        background: #fafafa;
                                        font-weight: 900;
                                        border-top: 1px solid #d4d4d8;
                                    }
                                    .dark .mk-table tfoot {
                                        background: #202023;
                                        border-color: #27272a;
                                    }
                                    .mk-table tfoot td {
                                        padding: 16px 20px;
                                        font-size: 11px;
                                    }
                                    .mk-total-cost {
                                        font-size: 15px;
                                        font-weight: 950;
                                        color: #e11d48;
                                    }
                                    .dark .mk-total-cost {
                                        color: #fb7185;
                                    }
                                </style>
                                ';

                                $html .= '<div class="mk-cost-container">';

                                // Grid de Tarjetas KPI
                                $html .= '  <div class="mk-kpi-grid">';

                                if ($tipo === 'subensamble') {
                                    // Tarjeta 1: Costo Total de Producción (Subreceta)
                                    $html .= '    <div class="mk-kpi-card mk-card-rose-border">';
                                    $html .= '      <div class="mk-kpi-header">';
                                    $html .= '        <span class="mk-kpi-title">Costo Total Subreceta</span>';
                                    $html .= '        <span class="mk-icon-wrapper mk-icon-rose">';
                                    $html .= '          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>';
                                    $html .= '        </span>';
                                    $html .= '      </div>';
                                    $html .= '      <div class="mk-kpi-value-container">';
                                    $html .= '        <span class="mk-kpi-value mk-color-rose">$ ' . number_format($totalCosto, 0, ',', '.') . '</span>';
                                    $html .= '        <span class="mk-kpi-label mk-badge-rose">BOM</span>';
                                    $html .= '      </div>';
                                    $html .= '    </div>';

                                    // Tarjeta 2: Insumo más Caro
                                    $nombreCaro = $insumoMasCaroId ? (Producto::find($insumoMasCaroId)?->nombre ?? 'Ninguno') : 'Ninguno';
                                    $html .= '    <div class="mk-kpi-card mk-card-amber-border">';
                                    $html .= '      <div class="mk-kpi-header">';
                                    $html .= '        <span class="mk-kpi-title">Insumo de Mayor Peso</span>';
                                    $html .= '        <span class="mk-icon-wrapper mk-icon-rose" style="background: rgba(245,158,11,0.1); color:#d97706;">';
                                    $html .= '          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>';
                                    $html .= '        </span>';
                                    $html .= '      </div>';
                                    $html .= '      <div class="mk-kpi-value-container">';
                                    $html .= '        <span class="mk-kpi-value mk-color-amber" style="font-size: 16px; max-width: 65%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="' . e(strtoupper($nombreCaro)) . '">' . strtoupper($nombreCaro) . '</span>';
                                    $html .= '        <span class="mk-kpi-label mk-badge-amber">$ ' . number_format($maxCostoItem, 0, ',', '.') . '</span>';
                                    $html .= '      </div>';
                                    $html .= '    </div>';
                                } else {
                                    // 1. PRECIO DE VENTA
                                    $html .= '    <div class="mk-kpi-card mk-card-blue-border">';
                                    $html .= '      <div class="mk-kpi-header">';
                                    $html .= '        <span class="mk-kpi-title">Precio de Venta</span>';
                                    $html .= '        <span class="mk-icon-wrapper mk-icon-blue">';
                                    $html .= '          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                    $html .= '        </span>';
                                    $html .= '      </div>';
                                    $html .= '      <div class="mk-kpi-value-container">';
                                    $html .= '        <span class="mk-kpi-value">$ ' . number_format($precioVenta, 0, ',', '.') . '</span>';
                                    $html .= '        <span class="mk-kpi-label" style="background: rgba(59,130,246,0.1); color:#2563eb;">VENTA</span>';
                                    $html .= '      </div>';
                                    $html .= '    </div>';

                                    // 2. COSTO TOTAL DE PRODUCCIÓN
                                    $html .= '    <div class="mk-kpi-card mk-card-rose-border">';
                                    $html .= '      <div class="mk-kpi-header">';
                                    $html .= '        <span class="mk-kpi-title">Costo Insumos</span>';
                                    $html .= '        <span class="mk-icon-wrapper mk-icon-rose">';
                                    $html .= '          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>';
                                    $html .= '        </span>';
                                    $html .= '      </div>';
                                    $html .= '      <div class="mk-kpi-value-container">';
                                    $html .= '        <span class="mk-kpi-value mk-color-rose">$ ' . number_format($totalCosto, 0, ',', '.') . '</span>';
                                    $html .= '        <span class="mk-kpi-label mk-badge-rose">BOM</span>';
                                    $html .= '      </div>';
                                    $html .= '    </div>';

                                    // 3. FOOD COST %
                                    $html .= '    <div class="mk-kpi-card mk-card-' . ($porcentajeCosto > 45 ? 'rose' : ($porcentajeCosto > 35 ? 'amber' : 'emerald')) . '-border">';
                                    $html .= '      <div class="mk-kpi-header">';
                                    $html .= '        <span class="mk-kpi-title">Food Cost %</span>';
                                    $html .= '        <span class="mk-kpi-label ' . $costoBadgeClass . '">' . $costoLabel . '</span>';
                                    $html .= '      </div>';
                                    $html .= '      <div class="mk-kpi-value-container" style="margin-top: 8px; flex-direction: column; justify-content: flex-start; align-items: flex-start;">';
                                    $html .= '        <span class="mk-kpi-value ' . $costoColorClass . '">' . number_format($porcentajeCosto, 1, ',', '.') . '%</span>';
                                    $html .= '        <div class="mk-progress-bg">';
                                    $html .= '          <div class="mk-progress-fill ' . $costoBgClass . '" style="width: ' . min($porcentajeCosto, 100) . '%"></div>';
                                    $html .= '        </div>';
                                    $html .= '      </div>';
                                    $html .= '    </div>';

                                    // 4. MARGEN / GANANCIA
                                    $html .= '    <div class="mk-kpi-card mk-card-emerald-border">';
                                    $html .= '      <div class="mk-kpi-header">';
                                    $html .= '        <span class="mk-kpi-title">Utilidad Bruta</span>';
                                    $html .= '        <span class="mk-icon-wrapper mk-icon-emerald">';
                                    $html .= '          <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>';
                                    $html .= '        </span>';
                                    $html .= '      </div>';
                                    $html .= '      <div class="mk-kpi-value-container">';
                                    $html .= '        <span class="mk-kpi-value ' . $utilidadColorClass . '">$ ' . number_format($beneficio, 0, ',', '.') . '</span>';
                                    $html .= '        <span class="mk-kpi-label ' . $utilidadBadgeClass . '">MARGEN</span>';
                                    $html .= '      </div>';
                                    $html .= '    </div>';
                                }

                                $html .= '  </div>'; // Fin KPIs

                                // Tabla Desglosada Estilo Premium
                                if (count($componentes) > 0) {
                                    $html .= '  <div class="mk-table-container">';
                                    
                                    // Header de la tabla
                                    $html .= '    <div class="mk-table-header">';
                                    $html .= '      <div>';
                                    $html .= '        <h4 class="mk-table-title">Desglose de Costos de Insumos</h4>';
                                    $html .= '        <p class="mk-table-subtitle">Estructura detallada y peso de cada insumo en la receta</p>';
                                    $html .= '      </div>';
                                    $html .= '      <span class="mk-table-badge">' . count($componentes) . ' Componentes</span>';
                                    $html .= '    </div>';
                                    
                                    $html .= '    <div class="mk-table-scroll">';
                                    $html .= '      <table class="mk-table">';
                                    $html .= '        <thead>';
                                    $html .= '          <tr>';
                                    $html .= '            <th style="text-align: left;">Insumo</th>';
                                    $html .= '            <th style="text-align: right;">Cantidad</th>';
                                    $html .= '            <th style="text-align: center;">Unidad</th>';
                                    $html .= '            <th style="text-align: right;">Costo Unitario</th>';
                                    $html .= '            <th style="text-align: right;">Costo Producción</th>';
                                    $html .= '            <th style="text-align: right; width: 22%;">Peso del Costo</th>';
                                    $html .= '          </tr>';
                                    $html .= '        </thead>';
                                    $html .= '        <tbody>';

                                    foreach ($componentes as $componente) {
                                        $productoHijoId = $componente['producto_hijo_id'] ?? null;
                                        $cantidad = floatval($componente['cantidad'] ?? 0);
                                        $costoItem = 0;
                                        $precioIngrediente = 0;
                                        $nombreItem = 'DESCONOCIDO';
                                        $unidadMedida = 'UND';
                                        
                                        if ($productoHijoId) {
                                            $productoHijo = Producto::with('unidadCompra')->find($productoHijoId);
                                            if ($productoHijo) {
                                                $nombreItem = $productoHijo->nombre;
                                                $precioIngrediente = floatval($productoHijo->getCostoUnitario());
                                                $costoItem = $precioIngrediente * $cantidad;
                                                $unidadMedida = $productoHijo->unidadCompra?->abreviatura ?? 'UND';
                                            }
                                        }

                                        $pesoPorcentaje = $totalCosto > 0 ? ($costoItem / $totalCosto) * 100 : 0;
                                        $esMasCaro = ($productoHijoId === $insumoMasCaroId && count($componentes) > 1);

                                        $html .= '        <tr>';
                                        $html .= '          <td style="font-weight: 700; color: var(--gray-800, #27272a);">';
                                        $html .= '            ' . strtoupper($nombreItem);
                                        if ($esMasCaro) {
                                            $html .= '        <span class="mk-table-tag">🔥 Mayor Costo</span>';
                                        }
                                        $html .= '          </td>';
                                        $html .= '          <td style="text-align: right; font-weight: 600; color: #71717a;">' . number_format($cantidad, 3, ',', '.') . '</td>';
                                        $html .= '          <td style="text-align: center; font-weight: 800; color: #a1a1aa;">' . strtoupper($unidadMedida) . '</td>';
                                        $html .= '          <td style="text-align: right; color: #71717a;">$ ' . number_format($precioIngrediente, 2, ',', '.') . '</td>';
                                        $html .= '          <td style="text-align: right; font-weight: 700; color: var(--gray-900, #18181b);">$ ' . number_format($costoItem, 0, ',', '.') . '</td>';
                                        
                                        // Visualización dinámica de porcentaje con barra estilizada
                                        $html .= '          <td>';
                                        $html .= '            <div class="mk-table-bar-container">';
                                        $html .= '              <span style="font-weight: 800; width: 38px; text-align: right;">' . number_format($pesoPorcentaje, 1, ',', '.') . '%</span>';
                                        $html .= '              <div class="mk-table-bar-bg">';
                                        $html .= '                <div class="mk-table-bar-fill ' . ($esMasCaro ? 'mk-bg-rose' : 'mk-bg-amber') . '" style="width: ' . $pesoPorcentaje . '%"></div>';
                                        $html .= '              </div>';
                                        $html .= '            </div>';
                                        $html .= '          </td>';
                                        $html .= '        </tr>';
                                    }

                                    $html .= '        </tbody>';
                                    $html .= '        <tfoot>';
                                    $html .= '          <tr>';
                                    $html .= '            <td style="text-transform: uppercase; font-weight: 900; letter-spacing: 0.05em; font-size: 9px; color: #71717a;">COSTO TOTAL DE RECETA</td>';
                                    $html .= '            <td colspan="3"></td>';
                                    $html .= '            <td style="text-align: right;" class="mk-total-cost">$ ' . number_format($totalCosto, 0, ',', '.') . '</td>';
                                    $html .= '            <td style="text-align: right; color: #10b981;" font-weight="900">100.0%</td>';
                                    $html .= '          </tr>';
                                    $html .= '        </tfoot>';
                                    $html .= '      </table>';
                                    $html .= '    </div>';
                                    $html .= '  </div>';
                                }

                                $html .= '</div>'; // Fin del contenedor principal

                                return new HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}