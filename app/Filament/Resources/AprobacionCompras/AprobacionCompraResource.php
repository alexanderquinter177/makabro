<?php

namespace App\Filament\Resources\AprobacionCompras;

use App\Filament\Resources\AprobacionCompras\Pages\ListAprobacionCompras;
use App\Filament\Resources\AprobacionCompras\Pages\ViewAprobacionCompra;
use App\Filament\Resources\Compras\Schemas\CompraForm;
use App\Filament\Resources\AprobacionCompras\Tables\AprobacionComprasTable;
use App\Models\Purchase\Compra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AprobacionCompraResource extends Resource
{
    protected static ?string $model = Compra::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|\UnitEnum|null $navigationGroup = 'Compras y Proveedores';

    protected static ?string $modelLabel = 'Aprobación de Compra';

    protected static ?string $pluralModelLabel = 'Aprobaciones de Compras';

    /**
     * Limitar el acceso únicamente a los usuarios que tengan el permiso para aprobar compras.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->activo && auth()->user()?->hasPermissionTo('compra.aprobar');
    }

    /**
     * Mostrar únicamente las compras que se encuentren en estado 'pendiente' (por aprobar).
     */
    public static function getEloquentQuery(): Builder
    {
        $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual;
        return parent::getEloquentQuery()
            ->where('status', 'pendiente')
            ->where('sede_id', $sedeId);
    }

    public static function form(Schema $schema): Schema
    {
        // Reutilizamos el formulario de compras (se visualizará como de solo lectura)
        return CompraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AprobacionComprasTable::configure($table);
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
            'index' => ListAprobacionCompras::route('/'),
            'view' => ViewAprobacionCompra::route('/{record}'),
        ];
    }
}
