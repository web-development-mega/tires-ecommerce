<?php

namespace App\Filament\Resources\TireSizes\Pages;

use App\Filament\Resources\TireSizes\TireSizeResource;
use App\Imports\TireSizesImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListTireSizes extends ListRecords
{
    protected static string $resource = TireSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importar')
                ->label('Importar desde Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('archivo')
                        ->label('Archivo Excel/CSV')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = storage_path('app/public/'.$data['archivo']);
                    Excel::import(new TireSizesImport, $file);

                    \Filament\Notifications\Notification::make()
                        ->title('Importación exitosa')
                        ->body('Los tamaños se importaron correctamente.')
                        ->success()
                        ->send();
                })
                ->modalHeading('Importar Tamaños de Llanta')
                ->modalDescription('Sube un archivo Excel o CSV con las columnas: ancho, perfil, diametro, slug, activo')
                ->modalSubmitActionLabel('Importar')
                ->modalFooterActions([
                    Action::make('descargar_plantilla')
                        ->label('Descargar plantilla de ejemplo')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->url(asset('storage/plantillas/tamanos-ejemplo.csv'), shouldOpenInNewTab: true),
                ]),
            CreateAction::make(),
        ];
    }
}
