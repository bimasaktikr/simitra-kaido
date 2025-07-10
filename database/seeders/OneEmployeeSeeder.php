<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class OneEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Jasmine Amalia Nastiti S.Tr.Stat.',
            'email' => 'jasmine.amalia@bps.go.id',
            'password' => bcrypt('bps3573'),
        ]);

        Employee::create([
            'name' => 'Jasmine Amalia Nastiti S.Tr.Stat.',
            'nip' => '199805162021042001',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1998-05-16',
            'team_id' => 3,
            'user_id' => $user->id,
        ]);
    }
}
