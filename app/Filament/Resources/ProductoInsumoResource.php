<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoInsumos\Pages\CreateProductoInsumo;
use App\Filament\Resources\ProductoInsumos\Pages\EditProductoInsumo;
use App\Filament\Resources\ProductoInsumos\Pages\ListProductoInsumos;
use App\Filament\Resources\Productos\Schemas\ProductoForm;
use App\Filament\Resources\Productos\Tables\ProductosTable;
use App\Models\Catalog\Producto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductoInsumoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?string $modelLabel = 'Insumo';

    protected static ?string $pluralModelLabel = 'Insumos / Materias Primas';

    protected static ?string $slug = 'productos-insumo';

    public static function form(Schema $schema): Schema
    {
        return ProductoForm::configure($schema, 'insumo');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tipo', 'insumo');
    }

    public static function table(Table $table): Table
    {
        return ProductosTable::configure($table, 'insumo');
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
            'index' => ListProductoInsumos::route('/'),
            'create' => CreateProductoInsumo::route('/create'),
            'edit' => EditProductoInsumo::route('/{record}/edit'),
        ];
    }
}
