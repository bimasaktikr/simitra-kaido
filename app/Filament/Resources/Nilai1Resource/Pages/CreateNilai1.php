<?php

namespace App\Filament\Resources\Nilai1Resource\Pages;

use App\Filament\Resources\Nilai1Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNilai1 extends CreateRecord
{
    protected static string $resource = Nilai1Resource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
