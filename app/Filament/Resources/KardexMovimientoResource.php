<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KardexMovimientos\Pages\ListKardexMovimientos;
use App\Filament\Resources\KardexMovimientos\Pages\ViewKardexMovimiento;
use App\Filament\Resources\KardexMovimientos\Schemas\KardexMovimientoForm;
use App\Filament\Resources\KardexMovimientos\Tables\KardexMovimientosTable;
use App\Models\Inventory\KardexMovimiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KardexMovimientoResource extends Resource
{
    protected static ?string $model = KardexMovimiento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static string|\UnitEnum|null $navigationGroup = 'Inventario y Movimientos';

    protected static ?string $modelLabel = 'Movimiento de Kardex';

    protected static ?string $pluralModelLabel = 'Kardex de Movimientos';

    public static function form(Schema $schema): Schema
    {
        return KardexMovimientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KardexMovimientosTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;
        return parent::getEloquentQuery()
            ->when($sedeId, fn ($query) => $query->where('kardex_movimientos.sede_id', $sedeId));
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
            'index' => ListKardexMovimientos::route('/'),
            'view' => ViewKardexMovimiento::route('/{record}'),
        ];
    }
}
