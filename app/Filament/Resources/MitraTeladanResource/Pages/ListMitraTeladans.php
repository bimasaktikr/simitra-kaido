<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMitraTeladans extends ListRecords
{
    protected static string $resource = MitraTeladanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
