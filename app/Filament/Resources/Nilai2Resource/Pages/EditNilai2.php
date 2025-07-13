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

    protected function mutateFormDataBeforeSave(array $data): array
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

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
