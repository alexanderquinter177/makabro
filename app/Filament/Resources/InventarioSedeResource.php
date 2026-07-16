<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarioSedes\Pages\CreateInventarioSede;
use App\Filament\Resources\InventarioSedes\Pages\EditInventarioSede;
use App\Filament\Resources\InventarioSedes\Pages\ListInventarioSedes;
use App\Filament\Resources\InventarioSedes\Schemas\InventarioSedeForm;
use App\Filament\Resources\InventarioSedes\Tables\InventarioSedesTable;
use App\Models\Inventory\InventarioSede;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventarioSedeResource extends Resource
{
    protected static ?string $model = InventarioSede::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario y Movimientos';

    protected static ?string $modelLabel = 'Stock de Sede';

    protected static ?string $pluralModelLabel = 'Stocks de Sedes';

    public static function form(Schema $schema): Schema
    {
        return InventarioSedeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventarioSedesTable::configure($table);
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
            'index' => ListInventarioSedes::route('/'),
            'create' => CreateInventarioSede::route('/create'),
            'edit' => EditInventarioSede::route('/{record}/edit'),
        ];
    }
}
