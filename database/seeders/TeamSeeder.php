<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            [
                'id' => 1,
                'name' => 'Umum',
                'has_survey' => false,
            ],
            [
                'id' => 2,
                'name' => 'Sosial',
                'has_survey' => true,
            ],
            [
                'id' => 3,
                'name' => 'Produksi',
                'has_survey' => true,
            ],
            [
                'id' => 4,
                'name' => 'Distribusi',
                'has_survey' => true,
            ],
            [
                'id' => 5,
                'name' => 'Neraca',
                'has_survey' => true,
            ],
            [
                'id' => 6,
                'name' => 'IPDS',
                'has_survey' => true,
            ],
        ]);
    }
}
