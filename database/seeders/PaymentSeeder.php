<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            ['id' => 1,
            'payment_type' => 'Orang Bulan'],
            ['id' => 2,
            'payment_type' => 'Dokumen'],
        ]);
    }
}
