<?php

namespace App\Filament\Resources\Nilai2Resource\Pages;

use App\Filament\Resources\Nilai2Resource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNilai2 extends CreateRecord
{
    protected static string $resource = Nilai2Resource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
