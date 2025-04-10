<?php

namespace App\Filament\Resources\MitraResource\Pages;

use App\Filament\Resources\MitraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;


class CreateMitra extends CreateRecord
{
    protected static string $resource = MitraResource::class;
    protected static bool $canCreateAnother = false;

    //customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Create a User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt('password'), // set a default password or generate
        ]);

        // Add user_id to data before Mitra is created
        $data['user_id'] = $user->id;

        return $data;
    }

}
