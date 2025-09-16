<?php

namespace App\Filament\Resources\MaximalPaymentResource\Pages;

use App\Filament\Resources\MaximalPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaximalPayment extends CreateRecord
{
    protected static string $resource = MaximalPaymentResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
    {
        if (\App\Models\MaximalPayment::query()->exists()) {
            $record = \App\Models\MaximalPayment::current();
            $this->redirect(static::getResource()::getUrl('edit', ['record' => $record]));
        }
    }

}
