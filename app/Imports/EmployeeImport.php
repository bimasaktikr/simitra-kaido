<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $name = $row['name'] ?? $row['nama'] ?? null;
            $nip = $row['nip'] ?? null;
            $jenis_kelamin = $row['jenis_kelamin'] ?? $row['gender'] ?? null;
            $tanggal_lahir = $row['tanggal_lahir'] ?? $row['birthdate'] ?? null;
            $email = $row['email'] ?? null;
            $team_name = $row['team_name'] ?? $row['tim'] ?? null;

            if (!$name || !$nip || !$jenis_kelamin || !$tanggal_lahir || !$email) {
                Log::warning('Skipped row: missing required fields', $row->toArray());
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make("bps3573"),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            Log::info($user->name . ' User Created');

            if (!$user->hasRole('employee')) {
                $user->assignRole('employee');
                Log::info($user->name . ' assigned role employee');
            }

            $team_id = null;
            if ($team_name) {
                $team = Team::firstOrCreate(['name' => $team_name]);
                $team_id = $team->id;
            }

            Employee::firstOrCreate(
                ['nip' => $nip],
                [
                    'name' => $name,
                    'jenis_kelamin' => $jenis_kelamin,
                    'tanggal_lahir' => $tanggal_lahir,
                    'user_id' => $user->id,
                    'team_id' => $team_id,
                ]
            );

            Log::info($user->name . ' employee created');
        }
    }
}
