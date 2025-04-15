<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMitraTeladan extends EditRecord
{
    protected static string $resource = MitraTeladanResource::class;

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
