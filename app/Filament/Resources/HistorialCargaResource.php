<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistorialCargas\Pages\CreateHistorialCarga;
use App\Filament\Resources\HistorialCargas\Pages\EditHistorialCarga;
use App\Filament\Resources\HistorialCargas\Pages\ListHistorialCargas;
use App\Filament\Resources\HistorialCargas\Pages\ViewHistorialCarga;
use App\Filament\Resources\HistorialCargas\RelationManagers\ProductosRelationManager;
use App\Filament\Resources\HistorialCargas\Schemas\HistorialCargaForm;
use App\Filament\Resources\HistorialCargas\Schemas\HistorialCargaInfolist;
use App\Filament\Resources\HistorialCargas\Tables\HistorialCargasTable;
use App\Models\Inventory\CargaHistorial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HistorialCargaResource extends Resource
{
    protected static ?string $model = CargaHistorial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario y Movimientos';

    protected static ?string $modelLabel = 'Acta de Entrega';

    protected static ?string $pluralModelLabel = 'Acta de Entregas';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return HistorialCargaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HistorialCargaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistorialCargasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHistorialCargas::route('/'),
            'create' => CreateHistorialCarga::route('/create'),
            'view' => ViewHistorialCarga::route('/{record}'),
            'edit' => EditHistorialCarga::route('/{record}/edit'),
        ];
    }
}
