<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoVentas\Pages\CreateProductoVenta;
use App\Filament\Resources\ProductoVentas\Pages\EditProductoVenta;
use App\Filament\Resources\ProductoVentas\Pages\ListProductoVentas;
use App\Filament\Resources\Productos\Schemas\ProductoForm;
use App\Filament\Resources\Productos\Tables\ProductosTable;
use App\Models\Catalog\Producto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductoVentaResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?string $modelLabel = 'Plato / Venta';

    protected static ?string $pluralModelLabel = 'Platos y Ventas';

    protected static ?string $slug = 'productos-venta';

    public static function form(Schema $schema): Schema
    {
        return ProductoForm::configure($schema, 'venta');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tipo', 'venta')
            ->with(['categoria', 'unidadCompra', 'sede']);
    }

    public static function table(Table $table): Table
    {
        return ProductosTable::configure($table, 'venta');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductoVentas::route('/'),
            'create' => CreateProductoVenta::route('/create'),
            'edit' => EditProductoVenta::route('/{record}/edit'),
        ];
    }
}
