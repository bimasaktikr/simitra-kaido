<?php

namespace App\Filament\Resources\Nilai2Resource\Pages;

use App\Filament\Resources\Nilai2Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilai2s extends ListRecords
{
    protected static string $resource = Nilai2Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
