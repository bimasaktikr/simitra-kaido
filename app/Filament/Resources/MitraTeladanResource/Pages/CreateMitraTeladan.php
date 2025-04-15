<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMitraTeladan extends CreateRecord
{
    protected static string $resource = MitraTeladanResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
