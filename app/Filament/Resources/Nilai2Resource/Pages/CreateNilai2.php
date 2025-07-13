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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;
        $count = 0;
        for ($i = 1; $i <= 10; $i++) {
            $value = (int) ($data['aspek' . $i] ?? 0);
            if ($value) {
                $total += $value;
                $count++;
            }
        }
        $data['rerata'] = $count > 0 ? round($total / $count, 2) : 0;
        return $data;
    }
}
