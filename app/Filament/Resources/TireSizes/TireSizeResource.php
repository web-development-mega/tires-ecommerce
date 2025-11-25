<?php

namespace App\Filament\Resources\TireSizes;

use App\Filament\Resources\TireSizes\Pages\CreateTireSize;
use App\Filament\Resources\TireSizes\Pages\EditTireSize;
use App\Filament\Resources\TireSizes\Pages\ListTireSizes;
use App\Filament\Resources\TireSizes\Pages\ViewTireSize;
use App\Filament\Resources\TireSizes\Schemas\TireSizeForm;
use App\Filament\Resources\TireSizes\Schemas\TireSizeInfolist;
use App\Filament\Resources\TireSizes\Tables\TireSizesTable;
use App\Models\TireSize;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TireSizeResource extends Resource
{
    protected static ?string $model = TireSize::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsPointingOut;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $navigationLabel = 'Tamaños';

    protected static ?string $modelLabel = 'Tamaño de llanta';

    protected static ?string $pluralModelLabel = 'Tamaños de llanta';

    public static function getNavigationGroup(): ?string
    {
        return 'Catálogo';
    }

    public static function form(Schema $schema): Schema
    {
        return TireSizeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TireSizeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TireSizesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTireSizes::route('/'),
            'create' => CreateTireSize::route('/create'),
            'view' => ViewTireSize::route('/{record}'),
            'edit' => EditTireSize::route('/{record}/edit'),
        ];
    }
}
