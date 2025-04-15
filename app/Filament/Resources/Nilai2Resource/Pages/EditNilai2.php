<?php

namespace App\Filament\Resources\Nilai2Resource\Pages;

use App\Filament\Resources\Nilai2Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNilai2 extends EditRecord
{
    protected static string $resource = Nilai2Resource::class;

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
