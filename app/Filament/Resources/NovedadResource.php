<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Novedades\Pages\CreateNovedad;
use App\Filament\Resources\Novedades\Pages\EditNovedad;
use App\Filament\Resources\Novedades\Pages\ListNovedades;
use App\Filament\Resources\Novedades\Schemas\NovedadForm;
use App\Filament\Resources\Novedades\Tables\NovedadesTable;
use App\Models\Inventory\Novedad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NovedadResource extends Resource
{
    protected static ?string $model = Novedad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario y Movimientos';

    protected static ?string $modelLabel = 'Novedad / Merma';

    protected static ?string $pluralModelLabel = 'Novedades y Mermas';

    public static function form(Schema $schema): Schema
    {
        return NovedadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NovedadesTable::configure($table);
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
            'index' => ListNovedades::route('/'),
            'create' => CreateNovedad::route('/create'),
            'edit' => EditNovedad::route('/{record}/edit'),
        ];
    }
}
