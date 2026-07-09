<?php

namespace App\Filament\Resources\Auditorias\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;

class AuditoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Auditoría')
                    ->schema([
                        Select::make('sede_id')
                            ->label('Sede')
                            ->options(Sede::pluck('nombre', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Seleccione la sede'),

                        Select::make('usuario_id')
                            ->label('Auditor / Usuario')
                            ->options(User::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->default(fn () => auth()->id())
                            ->placeholder('Seleccione el usuario'),

                        DatePicker::make('fecha_auditoria')
                            ->label('Fecha de Auditoría')
                            ->required()
                            ->default(now()),

                        Select::make('tipo_auditoria')
                            ->label('Tipo de Auditoría')
                            ->options([
                                'sorpresa' => 'Sorpresa',
                                'programada' => 'Programada',
                                'arqueo' => 'Arqueo',
                            ])
                            ->required()
                            ->placeholder('Seleccione tipo'),

                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'en_progreso' => 'En Progreso',
                                'completada' => 'Completada',
                            ])
                            ->required()
                            ->default('pendiente'),

                        TextInput::make('diferencia_encontrada')
                            ->label('Diferencia Encontrada')
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->placeholder('0.00')
                            ->helperText('Diferencia económica encontrada en auditoría/arqueo'),

                        Textarea::make('hallazgos')
                            ->label('Hallazgos / Novedades Encontradas')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Textarea::make('acciones_tomadas')
                            ->label('Acciones Tomadas')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
