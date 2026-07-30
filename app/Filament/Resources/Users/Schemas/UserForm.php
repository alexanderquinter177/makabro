<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Models\Catalog\Sede;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Grid superior: Información del Usuario
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Datos personales
                        Section::make('Información Personal')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre Completo')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Carlos Mario Restrepo')
                                    ->prefixIcon('heroicon-o-user'),

                                TextInput::make('cedula')
                                    ->label('Cédula')
                                    ->required()
                                    ->maxLength(20)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: 1020456789')
                                    ->prefixIcon('heroicon-o-identification'),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('Ej: carlos@makabro.com')
                                    ->prefixIcon('heroicon-o-envelope'),

                                TextInput::make('telefono')
                                    ->label('Teléfono')
                                    ->tel()
                                    ->maxLength(20)
                                    ->placeholder('Ej: 3101234567')
                                    ->prefixIcon('heroicon-o-phone'),
                            ])
                            ->columnSpan(1),

                        // COLUMNA DERECHA: Credenciales y rol
                        Section::make('Credenciales y Acceso')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                TextInput::make('password')
                                    ->label('Contraseña')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->placeholder(fn (string $operation) => $operation === 'edit' ? 'Dejar en blanco para no cambiar' : '********')
                                    ->prefixIcon('heroicon-o-key')
                                    ->revealable(),

                                TextInput::make('cargo')
                                    ->label('Cargo General')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Administrador, Cocinero, Cajero')
                                    ->prefixIcon('heroicon-o-briefcase'),

                                Select::make('roles')
                                    ->label('Roles de Acceso')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->placeholder('Seleccione los roles del usuario')
                                    ->options(function () {
                                        return Role::all()->pluck('name', 'id')->map(function ($name) {
                                            return ucfirst(str_replace('_', ' ', $name));
                                        });
                                    })
                                    ->helperText('Los roles determinan los permisos del usuario'),

                                Toggle::make('activo')
                                    ->label('Usuario Activo')
                                    ->default(true)
                                    ->helperText('Habilita o deshabilita el acceso total al sistema')
                                    ->onColor('success')
                                    ->offColor('danger'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),

                // SECCIÓN ABAJO: Asignación de Sedes
                Section::make('Asignación de Sedes')
                    ->icon('heroicon-o-building-office')
                    ->description('Asigne las sedes a las que este usuario tiene acceso y su rol o cargo en cada una.')
                    ->schema([
                        Repeater::make('userSedes')
                            ->relationship('userSedes')
                            ->schema([
                                Select::make('sede_id')
                                    ->label('Sede')
                                    ->options(Sede::where('activo', true)->pluck('nombre', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Seleccione sede')
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-building-office-2'),

                                TextInput::make('cargo_sede')
                                    ->label('Cargo en esta Sede')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Cajero Principal, Gerente Sede')
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-briefcase'),

                                Toggle::make('activo')
                                    ->label('Acceso Activo')
                                    ->default(true)
                                    ->columnSpan(1)
                                    ->onColor('success')
                                    ->offColor('danger'),
                            ])
                            ->columns(5)
                            ->defaultItems(0)
                            ->createItemButtonLabel('➕ Asignar a sede')
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'p-2 bg-gray-50 dark:bg-gray-800 rounded-lg']),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}