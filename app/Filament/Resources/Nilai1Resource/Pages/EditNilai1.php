<?php

namespace App\Filament\Resources\Nilai1Resource\Pages;

use App\Filament\Resources\Nilai1Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNilai1 extends EditRecord
{
    protected static string $resource = Nilai1Resource::class;

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
