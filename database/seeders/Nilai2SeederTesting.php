<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\MitraTeladan;
use App\Models\Nilai2;

class Nilai2SeederTesting extends Seeder
{
    public function run()
    {
        $year = now()->year;
        $quarter = 1;

        $mitraTeladans = MitraTeladan::where('year', $year)->where('quarter', $quarter)->get();
        $employees = Employee::all();

        foreach ($mitraTeladans as $mitraTeladan) {
            foreach ($employees as $employee) {
                $user = $employee->user;
                if (!$user) continue;

                // Skip if already exists
                if (Nilai2::where('mitra_teladan_id', $mitraTeladan->id)->where('user_id', $user->id)->exists()) {
                    continue;
                }

                Nilai2::create([
                    'mitra_teladan_id' => $mitraTeladan->id,
                    'user_id' => $user->id,
                    'aspek1' => rand(8, 10),
                    'aspek2' => rand(8, 10),
                    'aspek3' => rand(8, 10),
                    'aspek4' => rand(8, 10),
                    'aspek5' => rand(8, 10),
                    'aspek6' => rand(8, 10),
                    'aspek7' => rand(8, 10),
                    'aspek8' => rand(8, 10),
                    'aspek9' => rand(8, 10),
                    'aspek10' => rand(8, 10),
                    'rerata' => 9, // or calculate average if you want
                    'is_final' => 1,
                ]);
            }
        }
    }
}
