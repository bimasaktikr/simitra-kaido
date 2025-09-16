<?php

namespace App\Filament\Resources\MaximalPaymentResource\Pages;

use App\Filament\Resources\MaximalPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaximalPayments extends ListRecords
{
    protected static string $resource = MaximalPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
