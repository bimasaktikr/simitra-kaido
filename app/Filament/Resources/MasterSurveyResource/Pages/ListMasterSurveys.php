<?php

namespace App\Filament\Resources\MasterSurveyResource\Pages;

use App\Filament\Resources\MasterSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterSurveys extends ListRecords
{
    protected static string $resource = MasterSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
