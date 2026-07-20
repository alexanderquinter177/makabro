<?php

namespace App\Filament\Resources\SalesReportImports\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Grid;

class SalesReportImportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Encabezado: datos del reporte ──────────────────────────────────
                Section::make('📋 Información del Reporte')
                    ->description('Datos generales del reporte de ventas importado')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('sede.nombre')
                                ->label('Sede')
                                ->icon('heroicon-o-building-storefront')
                                ->weight('bold')
                                ->color('primary'),

                            TextEntry::make('date_range')
                                ->label('Rango de Fechas del Reporte')
                                ->icon('heroicon-o-calendar-date-range')
                                ->weight('bold')
                                ->badge()
                                ->color('success'),
                        ]),

                        Grid::make(2)->schema([
                            TextEntry::make('file_name')
                                ->label('Archivo Importado')
                                ->icon('heroicon-o-paper-clip')
                                ->color('gray')
                                ->copyable()
                                ->copyMessage('Nombre copiado')
                                ->fontFamily('mono'),

                            TextEntry::make('created_at')
                                ->label('Fecha y Hora de Importación')
                                ->icon('heroicon-o-clock')
                                ->dateTime('d/m/Y H:i:s'),
                        ]),
                    ]),

                // ── Resumen numérico ────────────────────────────────────────────────
                Section::make('📊 Resumen de la Importación')
                    ->description('Estadísticas rápidas del resultado de esta carga')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('items_count')
                                ->label('Total de Líneas')
                                ->state(fn ($record) => $record->items()->count())
                                ->icon('heroicon-o-list-bullet')
                                ->badge()
                                ->color('gray'),

                            TextEntry::make('items_cargados')
                                ->label('✅ Procesados (en stock)')
                                ->state(fn ($record) => $record->items()->whereNotNull('product_id')->count())
                                ->icon('heroicon-o-check-circle')
                                ->badge()
                                ->color('success'),

                            TextEntry::make('items_no_cargados')
                                ->label('⚠️ No en stock de sede')
                                ->state(fn ($record) => $record->items()->whereNull('product_id')->count())
                                ->icon('heroicon-o-exclamation-triangle')
                                ->badge()
                                ->color('danger'),
                        ]),
                    ]),
            ]);
    }
}
