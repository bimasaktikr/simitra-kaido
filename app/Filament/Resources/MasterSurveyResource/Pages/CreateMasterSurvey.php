<?php

namespace App\Filament\Resources\MasterSurveyResource\Pages;

use App\Filament\Resources\MasterSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMasterSurvey extends CreateRecord
{
    protected static string $resource = MasterSurveyResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
