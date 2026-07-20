<?php

namespace App\Filament\Resources\SalesReportImports;

use App\Filament\Resources\SalesReportImports\Pages\ListSalesReportImports;
use App\Filament\Resources\SalesReportImports\Pages\ViewSalesReportImport;
use App\Filament\Resources\SalesReportImports\Schemas\SalesReportImportInfolist;
use App\Filament\Resources\SalesReportImports\Tables\SalesReportImportsTable;
use App\Models\Inventory\SalesReportImport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesReportImportResource extends Resource
{
    protected static ?string $model = SalesReportImport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario y Movimientos';

    protected static ?string $modelLabel = 'Reporte de Ventas';

    protected static ?string $pluralModelLabel = 'Reportes de Ventas';

    protected static ?string $recordTitleAttribute = 'date_range';

    public static function infolist(Schema $schema): Schema
    {
        return SalesReportImportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesReportImportsTable::configure($table);
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalesReportImports::route('/'),
            'view'  => ViewSalesReportImport::route('/{record}'),
        ];
    }
}
