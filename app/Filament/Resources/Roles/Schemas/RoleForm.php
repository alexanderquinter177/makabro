<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $modulos = static::getModulosAgrupados();

        return $schema
            ->components([
                // Usar Grid de 2 columnas para poner el campo a la izquierda
                Grid::make(2)
                    ->schema([
                        // COLUMNA IZQUIERDA: Información del Rol
                        Section::make('Información del Rol')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre del Rol')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Ej: administrador_sede')
                                    ->helperText('Nombre único para identificar el rol')
                                    ->prefixIcon('heroicon-o-tag'),

                                TextInput::make('guard_name')
                                    ->label('Guard')
                                    ->required()
                                    ->default('web')
                                    ->maxLength(255)
                                    ->hidden(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // SECCIÓN ABAJO: Permisos Asociados (ocupa todo el ancho)
                Section::make('Permisos Asociados')
                    ->icon('heroicon-o-shield-check')
                    ->description('Seleccione los permisos que tendrá este rol')
                    ->schema([
                        Grid::make(3) // 3 columnas para los módulos
                            ->schema($modulos)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Obtener módulos agrupados como componentes CheckboxList
     */
    private static function getModulosAgrupados(): array
    {
        $groups = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'otros';
        });

        $components = [];

        foreach ($groups as $module => $permissions) {
            // Formatear nombre del módulo
            $moduleName = Str::title(str_replace('_', ' ', $module));
            $moduleIcon = static::getModuleIcon($module);

            $components[] = Section::make($moduleName)
                ->icon($moduleIcon)
                ->schema([
                    CheckboxList::make('permissions_' . $module)
                        ->label('')
                        ->options($permissions->pluck('name', 'id')->map(function ($name) {
                            $partes = explode('.', $name);
                            return Str::title(str_replace('_', ' ', $partes[1] ?? $name));
                        }))
                        ->columns(2) // Los checkboxes en 2 columnas dentro del módulo
                        ->bulkToggleable()
                        ->dehydrated(false)
                        ->afterStateHydrated(function ($component, $state, $record) use ($module) {
                            if ($record) {
                                $selected = $record->permissions
                                    ->filter(fn($p) => str_starts_with($p->name, $module . '.'))
                                    ->pluck('id')
                                    ->toArray();
                                $component->state($selected);
                            }
                        })
                        ->afterStateUpdated(function ($state, $set, $record, $get) use ($module) {
                            // Obtener todos los permisos del módulo
                            $modulePermissions = Permission::where('name', 'like', $module . '.%')->get();
                            $moduleIds = $modulePermissions->pluck('id')->toArray();
                            
                            // Obtener los permisos actuales
                            $currentPermissions = $get('permissions') ?? [];
                            
                            // Remover los permisos de este módulo
                            $otherPermissions = array_diff($currentPermissions, $moduleIds);
                            
                            // Combinar con los nuevos seleccionados
                            $newPermissions = array_merge($otherPermissions, $state ?? []);
                            
                            $set('permissions', $newPermissions);
                        }),
                ])
                ->collapsible()
                ->collapsed(true)
                ->extraAttributes(['class' => 'border border-gray-200 rounded-lg p-4 mb-2']);
        }

        return $components;
    }

    /**
     * Obtener ícono según el módulo
     */
    private static function getModuleIcon(string $module): string
    {
        $icons = [
            'auditoria' => 'heroicon-o-clipboard-document-check',
            'usuario' => 'heroicon-o-users',
            'categoria' => 'heroicon-o-tag',
            'producto' => 'heroicon-o-shopping-bag',
            'proveedor' => 'heroicon-o-truck',
            'sede' => 'heroicon-o-building-office',
            'unidad' => 'heroicon-o-scale',
            'inventario' => 'heroicon-o-cube',
            'inventario_sede' => 'heroicon-o-cube',
            'kardex' => 'heroicon-o-chart-bar',
            'novedad' => 'heroicon-o-exclamation-triangle',
            'compra' => 'heroicon-o-shopping-cart',
            'compra_item' => 'heroicon-o-shopping-cart',
        ];

        return $icons[$module] ?? 'heroicon-o-cog-6-tooth';
    }
}