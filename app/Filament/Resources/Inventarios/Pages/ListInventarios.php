<?php

namespace App\Filament\Resources\Inventarios\Pages;

use App\Filament\Resources\InventarioResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use App\Models\Catalog\Producto;
use App\Models\Catalog\Sede;
use App\Models\Inventory\InventarioSede;
use App\Services\Inventory\CargaInicialImporter;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListInventarios extends ListRecords
{
    protected static string $resource = InventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}