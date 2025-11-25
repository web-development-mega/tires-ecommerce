<?php

namespace App\Filament\Resources\TireSizes\Pages;

use App\Filament\Resources\TireSizes\TireSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTireSize extends EditRecord
{
    protected static string $resource = TireSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
