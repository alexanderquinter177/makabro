<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoSubensambles\Pages\CreateProductoSubensamble;
use App\Filament\Resources\ProductoSubensambles\Pages\EditProductoSubensamble;
use App\Filament\Resources\ProductoSubensambles\Pages\ListProductoSubensambles;
use App\Filament\Resources\Productos\Schemas\ProductoForm;
use App\Filament\Resources\Productos\Tables\ProductosTable;
use App\Models\Catalog\Producto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductoSubensambleResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?string $modelLabel = 'Subensamble';

    protected static ?string $pluralModelLabel = 'Subensambles / Recetas';

    protected static ?string $slug = 'productos-subensamble';

    public static function form(Schema $schema): Schema
    {
        return ProductoForm::configure($schema, 'subensamble');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tipo', 'subensamble');
    }

    public static function table(Table $table): Table
    {
        return ProductosTable::configure($table, 'subensamble');
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
            'index' => ListProductoSubensambles::route('/'),
            'create' => CreateProductoSubensamble::route('/create'),
            'edit' => EditProductoSubensamble::route('/{record}/edit'),
        ];
    }
}
