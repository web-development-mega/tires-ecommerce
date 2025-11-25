<?php

namespace App\Filament\Resources\TireSizes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TireSizeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('width')
                    ->numeric(),
                TextEntry::make('aspect_ratio')
                    ->numeric(),
                TextEntry::make('rim_diameter')
                    ->numeric(),
                TextEntry::make('slug'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
