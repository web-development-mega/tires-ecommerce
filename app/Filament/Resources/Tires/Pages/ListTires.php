<?php

namespace App\Filament\Resources\Tires\Pages;

use App\Filament\Resources\Tires\TireResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTires extends ListRecords
{
    protected static string $resource = TireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
