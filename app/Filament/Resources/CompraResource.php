<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Compras\Pages\CreateCompra;
use App\Filament\Resources\Compras\Pages\EditCompra;
use App\Filament\Resources\Compras\Pages\ListCompras;
use App\Filament\Resources\Compras\Schemas\CompraForm;
use App\Filament\Resources\Compras\Tables\ComprasTable;
use App\Models\Purchase\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|\UnitEnum|null $navigationGroup = 'Compras y Proveedores';

    protected static ?string $modelLabel = 'Compra / Factura';

    protected static ?string $pluralModelLabel = 'Compras y Facturas';

    public static function form(Schema $schema): Schema
    {
        return CompraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComprasTable::configure($table);
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
            'index' => ListCompras::route('/'),
            'create' => CreateCompra::route('/create'),
            'edit' => EditCompra::route('/{record}/edit'),
        ];
    }
}
