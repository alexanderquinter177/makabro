<?php

namespace App\Filament\Resources\HistorialCargas\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class HistorialCargaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ── Información Principal de la Carga ─────────────────────────────
                Section::make('📋 Información General de la Carga')
                    ->description('Detalles del registro maestro y responsable de la entrega')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('sede.nombre')
                                ->label('Sede')
                                ->icon('heroicon-o-building-office-2')
                                ->weight('bold')
                                ->badge()
                                ->color('primary')
                                ->default('—'),

                            TextEntry::make('fecha')
                                ->label('Fecha de Carga')
                                ->icon('heroicon-o-calendar')
                                ->date('d/m/Y')
                                ->weight('bold')
                                ->color('primary'),

                            TextEntry::make('tipo')
                                ->label('Tipo de Entrega')
                                ->icon('heroicon-o-tag')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Entrega de barra' => 'info',
                                    'Entrega de cocina' => 'warning',
                                    default => 'gray',
                                }),

                            TextEntry::make('cargo_recibe')
                                ->label('Cargo de Quien Recibe')
                                ->icon('heroicon-o-briefcase')
                                ->badge()
                                ->color('warning')
                                ->default('—'),

                            TextEntry::make('nombre_recibe')
                                ->label('Nombre de Quien Recibe')
                                ->icon('heroicon-o-user')
                                ->weight('bold')
                                ->badge()
                                ->color('gray')
                                ->default('—'),
                        ]),
                    ]),

                // ── Métricas y Valores ───────────────────────────────────────────
                Section::make('📊 Resumen Financiero y Totales')
                    ->description('Totales calculados de los productos ingresados en este historial')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('valor_total')
                                ->label('Valor Total Maestro')
                                ->icon('heroicon-o-currency-dollar')
                                ->numeric(decimalPlaces: 2)
                                ->prefix('$')
                                ->weight('bold')
                                ->color('success')
                                ->size('lg'),

                            TextEntry::make('productos_count')
                                ->label('Total Productos Cargados')
                                ->icon('heroicon-o-cube')
                                ->state(fn ($record) => $record->productos()->count() . ' ítems')
                                ->badge()
                                ->color('primary')
                                ->size('lg'),

                            TextEntry::make('created_at')
                                ->label('Fecha y Hora de Registro')
                                ->icon('heroicon-o-clock')
                                ->dateTime('d/m/Y H:i:s')
                                ->color('gray'),
                        ]),
                    ]),
            ]);
    }
}
