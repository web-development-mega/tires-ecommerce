<?php

namespace App\Filament\Resources\Tires\Pages;

use App\Filament\Resources\Tires\TireResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTire extends ViewRecord
{
    protected static string $resource = TireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
