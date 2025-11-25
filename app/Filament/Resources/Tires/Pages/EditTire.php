<?php

namespace App\Filament\Resources\Tires\Pages;

use App\Filament\Resources\Tires\TireResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTire extends EditRecord
{
    protected static string $resource = TireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
