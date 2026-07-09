<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnidadesMedida\Pages\CreateUnidadMedida;
use App\Filament\Resources\UnidadesMedida\Pages\EditUnidadMedida;
use App\Filament\Resources\UnidadesMedida\Pages\ListUnidadesMedida;
use App\Filament\Resources\UnidadesMedida\Schemas\UnidadMedidaForm;
use App\Filament\Resources\UnidadesMedida\Tables\UnidadesMedidaTable;
use App\Models\Catalog\UnidadMedida;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnidadMedidaResource extends Resource
{
    protected static ?string $model = UnidadMedida::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogo';

    protected static ?string $modelLabel = 'Unidad de Medida';

    protected static ?string $pluralModelLabel = 'Unidades de Medida';

    public static function form(Schema $schema): Schema
    {
        return UnidadMedidaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnidadesMedidaTable::configure($table);
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
            'index' => ListUnidadesMedida::route('/'),
            'create' => CreateUnidadMedida::route('/create'),
            'edit' => EditUnidadMedida::route('/{record}/edit'),
        ];
    }
}
