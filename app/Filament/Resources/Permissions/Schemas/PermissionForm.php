<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Str;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información del Permiso
                        Section::make('Información del Permiso')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Select::make('module')
                                    ->label('Módulo')
                                    ->required()
                                    ->options([
                                        'auditoria' => 'Auditoría',
                                        'usuario' => 'Usuario',
                                        'categoria' => 'Categoría',
                                        'producto' => 'Producto',
                                        'proveedor' => 'Proveedor',
                                        'sede' => 'Sede',
                                        'unidad' => 'Unidad de Medida',
                                        'inventario' => 'Inventario',
                                        'inventario_sede' => 'Inventario por Sede',
                                        'kardex' => 'Kardex',
                                        'novedad' => 'Novedad',
                                        'compra' => 'Compra',
                                        'compra_item' => 'Ítem de Compra',
                                    ])
                                    ->searchable()
                                    ->placeholder('Seleccione el módulo')
                                    ->helperText('Módulo al que pertenece el permiso')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $action = $get('action');
                                        if ($state && $action) {
                                            $set('name', $state . '.' . $action);
                                        }
                                    })
                                    ->columnSpan(1),

                                Select::make('action')
                                    ->label('Acción')
                                    ->required()
                                    ->options([
                                        'ver_listado' => 'Ver Listado',
                                        'ver_detalle' => 'Ver Detalle',
                                        'crear' => 'Crear',
                                        'editar' => 'Editar',
                                        'eliminar' => 'Eliminar',
                                        'restaurar' => 'Restaurar',
                                        'eliminar_permanente' => 'Eliminar Permanente',
                                        'aprobar' => 'Aprobar',
                                        'rechazar' => 'Rechazar',
                                        'exportar' => 'Exportar',
                                        'importar' => 'Importar',
                                        'cambiar_precio' => 'Cambiar Precio',
                                        'cambiar_stock' => 'Cambiar Stock',
                                        'cambiar_estado' => 'Cambiar Estado',
                                        'asignar' => 'Asignar',
                                        'resolver' => 'Resolver',
                                        'pagar' => 'Pagar',
                                        'recibir' => 'Recibir',
                                        'pdf' => 'Generar PDF',
                                        'reporte' => 'Reporte',
                                        'resetear_password' => 'Resetear Contraseña',
                                        'asignar_rol' => 'Asignar Rol',
                                        'asignar_usuario' => 'Asignar Usuario',
                                        'change_status' => 'Cambiar Estado',
                                        'duplicate' => 'Duplicar',
                                        'adjust_cost' => 'Ajustar Costo',
                                    ])
                                    ->searchable()
                                    ->placeholder('Seleccione la acción')
                                    ->helperText('Acción que se va a permitir')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $module = $get('module');
                                        if ($module && $state) {
                                            $set('name', $module . '.' . $state);
                                        }
                                    })
                                    ->columnSpan(1),

                                TextInput::make('name')
                                    ->label('Nombre del Permiso (Auto-generado)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: producto.crear')
                                    ->helperText('Formato: modulo.accion')
                                    ->prefixIcon('heroicon-o-tag')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1),

                        // COLUMNA DERECHA: Información adicional
                        Section::make('Información Adicional')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('guard_name')
                                    ->label('Guard')
                                    ->required()
                                    ->default('web')
                                    ->maxLength(255)
                                    ->placeholder('web')
                                    ->helperText('Guarda de seguridad para el permiso')
                                    ->prefixIcon('heroicon-o-shield-exclamation')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('description')
                                    ->label('Descripción')
                                    ->maxLength(500)
                                    ->placeholder('Descripción breve del permiso...')
                                    ->helperText('Describe qué hace este permiso')
                                    ->prefixIcon('heroicon-o-document-text')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}