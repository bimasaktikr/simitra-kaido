<?php

namespace App\Filament\Resources\MaximalPaymentResource\Pages;

use App\Filament\Resources\MaximalPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaximalPayment extends EditRecord
{
    protected static string $resource = MaximalPaymentResource::class;

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
