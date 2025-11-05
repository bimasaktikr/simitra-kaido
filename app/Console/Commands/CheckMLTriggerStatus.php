<?php

namespace App\Console\Commands;

use App\Models\Survey;
use App\Services\MLRecommendationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckMLTriggerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:check-trigger 
                            {surveyId? : Survey ID to check}
                            {--all : Show all surveys with trigger status}
                            {--not-triggered : Show only surveys that haven\'t been triggered}
                            {--triggered : Show only triggered surveys}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check ML trigger status for surveys (finalized but not yet synced to PostgreSQL)';

    protected MLRecommendationService $mlService;

    public function __construct(MLRecommendationService $mlService)
    {
        parent::__construct();
        $this->mlService = $mlService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $surveyId = $this->argument('surveyId');

        // Show all surveys with status
        if ($this->option('all')) {
            $this->showAllSurveysStatus();
            return 0;
        }

        // Show only not-triggered surveys
        if ($this->option('not-triggered')) {
            $this->showNotTriggeredSurveys();
            return 0;
        }

        // Show only triggered surveys
        if ($this->option('triggered')) {
            $this->showTriggeredSurveys();
            return 0;
        }

        if (!$surveyId) {
            // Show recent surveys summary
            $this->showRecentSurveys();
            return 0;
        }

        // Check specific survey
        $this->checkSurvey($surveyId);
        return 0;
    }

    protected function showRecentSurveys()
    {
        $this->info("📋 Recent Surveys (Last 10):\n");

        $surveys = Survey::with(['masterSurvey', 'transaction'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $headers = ['ID', 'Name', 'Master Survey', 'Status', 'Finalized', 'Transactions', 'ML Ready?'];
        $rows = [];

        foreach ($surveys as $survey) {
            $rows[] = [
                $survey->id,
                substr($survey->name, 0, 30),
                $survey->masterSurvey?->name ?? '-',
                $survey->status ?? 'pending',
                $survey->is_scored ? '✅ Yes' : '❌ No',
                $survey->transaction->count(),
                $this->checkMLReady($survey) ? '✅ Ready' : '⚠️ Not Ready'
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("💡 Use: php artisan ml:check-trigger {surveyId} to check specific survey");
    }

    protected function checkSurvey(int $surveyId)
    {
        $survey = Survey::with(['masterSurvey', 'transaction.nilai', 'transaction.mitra'])
            ->find($surveyId);

        if (!$survey) {
            $this->error("❌ Survey ID {$surveyId} not found!");
            return;
        }

        $this->info("🔍 Checking Survey ID: {$surveyId}");
        $this->info("📝 Name: {$survey->name}");
        $this->newLine();

        // Check 1: Master Survey
        $this->line("1️⃣ Master Survey Check:");
        if ($survey->masterSurvey) {
            $this->info("   ✅ Master Survey: {$survey->masterSurvey->name} (ID: {$survey->masterSurvey->id})");
            $this->info("   ✅ Type: {$survey->masterSurvey->type}");
        } else {
            $this->error("   ❌ No Master Survey linked!");
        }
        $this->newLine();

        // Check 2: Transactions
        $totalTx = $survey->transaction->count();
        $this->line("2️⃣ Transaction Check:");
        $this->info("   ✅ Total Transactions: {$totalTx}");
        
        if ($totalTx === 0) {
            $this->warn("   ⚠️ No transactions (mitras) assigned yet!");
        }
        $this->newLine();

        // Check 3: Nilai (Ratings)
        $this->line("3️⃣ Nilai (Rating) Check:");
        $txWithNilai = $survey->transaction->filter(fn($tx) => $tx->nilai !== null)->count();
        $txWithoutNilai = $totalTx - $txWithNilai;

        $this->info("   ✅ Transactions with Nilai: {$txWithNilai}/{$totalTx}");
        
        if ($txWithoutNilai > 0) {
            $this->warn("   ⚠️ {$txWithoutNilai} transactions missing nilai!");
            
            // List mitras without nilai
            $missingNilai = $survey->transaction
                ->filter(fn($tx) => $tx->nilai === null)
                ->map(fn($tx) => "      - {$tx->mitra->name} (Mitra ID: {$tx->mitra_id})")
                ->take(5);
            
            $this->line("   Missing nilai for:");
            foreach ($missingNilai as $missing) {
                $this->line($missing);
            }
            
            if ($txWithoutNilai > 5) {
                $this->line("      ... and " . ($txWithoutNilai - 5) . " more");
            }
        }
        $this->newLine();

        // Check 4: Finalization Status
        $this->line("4️⃣ Finalization Status:");
        if ($survey->is_scored) {
            $this->info("   ✅ Survey is finalized (is_scored = true)");
        } else {
            $this->warn("   ⚠️ Survey NOT finalized yet (is_scored = false)");
        }
        $this->newLine();

        // Check 5: ML API Status
        $this->line("5️⃣ ML API Connectivity:");
        try {
            $type = $survey->masterSurvey?->type ?? 'Rumah Tangga';
            $recommendations = $this->mlService->getRecommendations($type, 5);
            
            if ($recommendations['success']) {
                $this->info("   ✅ ML API reachable");
                $this->info("   ✅ Endpoint: /rekomendasi/" . strtolower(str_replace(' ', '_', $type)));
            } else {
                $this->error("   ❌ ML API error: {$recommendations['message']}");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Cannot reach ML API: " . $e->getMessage());
        }
        $this->newLine();

        // Check 6: PostgreSQL Sync Status
        $this->line("6️⃣ PostgreSQL Sync Check:");
        try {
            $pgCount = DB::connection('pgsql')->table('mitra_survey_data')
                ->where('survey_id', $surveyId)
                ->count();
            
            if ($pgCount > 0) {
                $this->info("   ✅ Found {$pgCount} records in PostgreSQL for this survey");
            } else {
                $this->warn("   ⚠️ No records in PostgreSQL yet (will sync on finalization)");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Cannot check PostgreSQL: " . $e->getMessage());
        }
        $this->newLine();

        // Final Verdict
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $mlReady = $this->checkMLReady($survey);
        
        if ($mlReady) {
            $this->info("✅ VERDICT: Survey is READY for ML trigger!");
            $this->newLine();
            $this->info("📋 What happens when you click 'Finalize & Retrain ML':");
            $this->line("   1. Survey status will be set to 'done'");
            $this->line("   2. is_scored will be set to true");
            $this->line("   3. Data will be synced to PostgreSQL");
            $this->line("   4. Airflow DAG will be triggered for ML retraining");
            $this->line("   5. PSO algorithm will optimize with new data");
            $this->newLine();
            $this->info("🚀 You can safely finalize this survey!");
        } else {
            $this->error("❌ VERDICT: Survey is NOT ready for ML trigger!");
            $this->newLine();
            $this->warn("⚠️ Issues to fix before finalization:");
            
            if (!$survey->masterSurvey) {
                $this->line("   • Link survey to a Master Survey");
            }
            
            if ($totalTx === 0) {
                $this->line("   • Add at least one mitra to the survey");
            }
            
            if ($txWithoutNilai > 0) {
                $this->line("   • Add nilai (ratings) for all {$txWithoutNilai} assigned mitras");
            }
            
            if (!$survey->is_scored) {
                $this->line("   • Click 'Finalize Nilai' button first");
            }
        }
        
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }

    protected function checkMLReady(Survey $survey): bool
    {
        // Check all requirements for ML trigger
        $hasMasterSurvey = $survey->master_survey_id !== null;
        $hasTransactions = $survey->transaction()->count() > 0;
        $allHaveNilai = $survey->transaction()
            ->whereDoesntHave('nilai')
            ->count() === 0;
        $isFinalized = $survey->is_scored;

        return $hasMasterSurvey && $hasTransactions && $allHaveNilai && $isFinalized;
    }

    /**
     * Show all surveys with their trigger status
     */
    protected function showAllSurveysStatus()
    {
        $this->info("📊 ALL SURVEYS - ML TRIGGER STATUS");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        $surveys = Survey::with(['masterSurvey', 'transaction.nilai'])
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        $triggered = 0;
        $notTriggered = 0;

        $tableData = [];
        foreach ($surveys as $survey) {
            $isTriggered = $survey->is_synced == 1;
            $isFinalized = $survey->is_scored == 1;
            $txCount = $survey->transaction()->count();
            $nilaiCount = DB::table('nilai1s')
                ->whereIn('transaction_id', $survey->transaction()->pluck('id'))
                ->count();

            if ($isTriggered) {
                $triggered++;
                $status = '✅ Triggered';
            } else if ($isFinalized && $txCount > 0 && $nilaiCount == $txCount) {
                $notTriggered++;
                $status = '⚠️ Ready (Not Triggered)';
            } else {
                $status = '⏳ Incomplete';
            }

            $tableData[] = [
                $survey->id,
                $survey->masterSurvey->name ?? 'N/A',
                $survey->status,
                $txCount,
                $nilaiCount,
                $isFinalized ? 'Yes' : 'No',
                $status,
            ];
        }

        $this->table(
            ['ID', 'Master Survey', 'Status', 'Mitras', 'Nilai', 'Finalized', 'ML Status'],
            $tableData
        );

        $this->newLine();
        $this->info("📈 Summary:");
        $this->line("   ✅ Triggered & synced: {$triggered}");
        $this->line("   ⚠️  Ready but not triggered: {$notTriggered}");
        $this->line("   ⏳ Incomplete: " . (count($tableData) - $triggered - $notTriggered));
    }

    /**
     * Show only surveys that haven't been triggered yet
     */
    protected function showNotTriggeredSurveys()
    {
        $this->warn("⚠️  SURVEYS READY BUT NOT TRIGGERED YET");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        $surveys = Survey::with(['masterSurvey', 'transaction.nilai'])
            ->where('is_scored', 1)
            ->where('is_synced', 0)
            ->orderBy('id', 'desc')
            ->get();

        if ($surveys->isEmpty()) {
            $this->info("✅ All finalized surveys have been triggered!");
            return;
        }

        $tableData = [];
        foreach ($surveys as $survey) {
            $txCount = $survey->transaction()->count();
            $nilaiCount = DB::table('nilai1s')
                ->whereIn('transaction_id', $survey->transaction()->pluck('id'))
                ->count();

            // Only show surveys that are truly ready
            if ($txCount > 0 && $nilaiCount == $txCount) {
                $tableData[] = [
                    $survey->id,
                    $survey->masterSurvey->name ?? 'N/A',
                    $survey->masterSurvey->type ?? 'N/A',
                    $txCount,
                    $nilaiCount,
                    $survey->updated_at->format('Y-m-d H:i'),
                ];
            }
        }

        if (empty($tableData)) {
            $this->info("✅ All finalized surveys have been triggered!");
            return;
        }

        $this->table(
            ['Survey ID', 'Master Survey', 'Type', 'Mitras', 'Nilai', 'Finalized At'],
            $tableData
        );

        $this->newLine();
        $this->warn("💡 To trigger these surveys, run:");
        $this->line("   php artisan ml:trigger <survey_id>");
        $this->line("   OR");
        $this->line("   php artisan ml:trigger --all");
    }

    /**
     * Show only surveys that have been triggered
     */
    protected function showTriggeredSurveys()
    {
        $this->info("✅ TRIGGERED SURVEYS (Synced to PostgreSQL)");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        $surveys = Survey::with(['masterSurvey', 'transaction'])
            ->where('is_synced', 1)
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        if ($surveys->isEmpty()) {
            $this->warn("⚠️  No surveys have been triggered yet!");
            return;
        }

        $tableData = [];
        foreach ($surveys as $survey) {
            $tableData[] = [
                $survey->id,
                $survey->masterSurvey->name ?? 'N/A',
                $survey->masterSurvey->type ?? 'N/A',
                $survey->transaction()->count(),
                $survey->updated_at->format('Y-m-d H:i'),
            ];
        }

        $this->table(
            ['Survey ID', 'Master Survey', 'Type', 'Mitras', 'Synced At'],
            $tableData
        );

        $this->newLine();
        $this->info("📊 Total triggered surveys: " . count($tableData));
    }
}
