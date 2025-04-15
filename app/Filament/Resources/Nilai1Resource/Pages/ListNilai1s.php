<?php

namespace App\Filament\Resources\Nilai1Resource\Pages;

use App\Filament\Resources\Nilai1Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilai1s extends ListRecords
{
    protected static string $resource = Nilai1Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
