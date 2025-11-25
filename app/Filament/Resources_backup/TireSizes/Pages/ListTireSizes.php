<?php

namespace App\Filament\Resources\TireSizes\Pages;

use App\Filament\Resources\TireSizes\TireSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTireSizes extends ListRecords
{
    protected static string $resource = TireSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
