<?php

namespace App\Filament\Resources\MasterSurveyResource\Pages;

use App\Filament\Resources\MasterSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterSurvey extends EditRecord
{
    protected static string $resource = MasterSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
