<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Inventarios\Pages\CreateInventario;
use App\Filament\Resources\Inventarios\Pages\EditInventario;
use App\Filament\Resources\Inventarios\Pages\ListInventarios;
use App\Filament\Resources\Inventarios\Schemas\InventarioForm;
use App\Filament\Resources\Inventarios\Tables\InventariosTable;
use App\Models\Inventory\Inventario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventarioResource extends Resource
{
    protected static ?string $model = Inventario::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario y Movimientos';

    protected static ?string $modelLabel = 'Inventario';

    protected static ?string $pluralModelLabel = 'Inventarios';

    public static function form(Schema $schema): Schema
    {
        return InventarioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventariosTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if (session()->has('sede_id')) {
            return parent::getEloquentQuery()->where('sede_id', session('sede_id'));
        }

        return parent::getEloquentQuery();
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
            'index' => ListInventarios::route('/'),
            'create' => CreateInventario::route('/create'),
            'edit' => EditInventario::route('/{record}/edit'),
        ];
    }
}
