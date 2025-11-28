<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterSurveyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder automatically sets the 'type' column for master_surveys
     * based on survey name patterns for ML recommendations.
     */
    public function run(): void
    {
        Log::info('🎯 Starting MasterSurveyTypeSeeder...');

        // Update surveys with 'Rumah Tangga' type
        $rtCount = DB::table('master_surveys')
            ->where(function($query) {
                $query->where('name', 'LIKE', '%Rumah Tangga%')
                      ->orWhere('name', 'LIKE', '%SUSENAS%')
                      ->orWhere('code', 'LIKE', '%RT%')
                      ->orWhere('code', 'LIKE', '%SUSENAS%');
            })
            ->whereNull('type')
            ->update(['type' => 'Rumah Tangga']);

        Log::info("✅ Updated {$rtCount} surveys to 'Rumah Tangga'");

        // Update surveys with 'Perusahaan' type
        $prCount = DB::table('master_surveys')
            ->where(function($query) {
                $query->where('name', 'LIKE', '%Perusahaan%')
                      ->orWhere('name', 'LIKE', '%Industri%')
                      ->orWhere('name', 'LIKE', '%Konstruksi%')
                      ->orWhere('name', 'LIKE', '%Perdagangan%')
                      ->orWhere('code', 'LIKE', '%PR%')
                      ->orWhere('code', 'LIKE', '%IND%')
                      ->orWhere('code', 'LIKE', '%KONST%');
            })
            ->whereNull('type')
            ->update(['type' => 'Perusahaan']);

        Log::info("✅ Updated {$prCount} surveys to 'Perusahaan'");

        // Log surveys that still have NULL type
        $nullCount = DB::table('master_surveys')
            ->whereNull('type')
            ->count();

        if ($nullCount > 0) {
            Log::warning("⚠️ {$nullCount} surveys still have NULL type. Please update them manually.");
            
            $nullSurveys = DB::table('master_surveys')
                ->whereNull('type')
                ->select('id', 'name', 'code')
                ->get();

            foreach ($nullSurveys as $survey) {
                Log::warning("   - ID: {$survey->id}, Name: {$survey->name}, Code: {$survey->code}");
            }
        }

        Log::info('✨ MasterSurveyTypeSeeder completed!');
        
        $this->command->info('✅ Master Survey types updated successfully!');
        $this->command->info("   - Rumah Tangga: {$rtCount} surveys");
        $this->command->info("   - Perusahaan: {$prCount} surveys");
        
        if ($nullCount > 0) {
            $this->command->warn("   - ⚠️ Still NULL: {$nullCount} surveys (check logs for details)");
        }
    }
}
