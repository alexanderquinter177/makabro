<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Auditorias\Pages\CreateAuditoria;
use App\Filament\Resources\Auditorias\Pages\EditAuditoria;
use App\Filament\Resources\Auditorias\Pages\ListAuditorias;
use App\Filament\Resources\Auditorias\Schemas\AuditoriaForm;
use App\Filament\Resources\Auditorias\Tables\AuditoriasTable;
use App\Models\Audit\Auditoria;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditoriaResource extends Resource
{
    protected static ?string $model = Auditoria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlassCircle;

    protected static string | UnitEnum | null $navigationGroup = 'Auditoría';

    protected static ?string $modelLabel = 'Auditoría';

    protected static ?string $pluralModelLabel = 'Auditorías';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriasTable::configure($table);
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
            'index' => ListAuditorias::route('/'),
            'create' => CreateAuditoria::route('/create'),
            'edit' => EditAuditoria::route('/{record}/edit'),
        ];
    }
}
