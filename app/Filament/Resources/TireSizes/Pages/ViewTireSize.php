<?php

namespace App\Filament\Resources\TireSizes\Pages;

use App\Filament\Resources\TireSizes\TireSizeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTireSize extends ViewRecord
{
    protected static string $resource = TireSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
