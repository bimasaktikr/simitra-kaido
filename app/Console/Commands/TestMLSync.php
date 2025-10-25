<?php

namespace App\Console\Commands;

use App\Models\Survey;
use App\Services\MLRecommendationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestMLSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:test-sync {surveyId : Survey ID to sync and trigger}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test ML sync and trigger for a specific survey (for debugging)';

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

        $survey = Survey::find($surveyId);

        if (!$survey) {
            $this->error("❌ Survey ID {$surveyId} not found!");
            return 1;
        }

        $this->info("🧪 Testing ML Sync & Trigger for Survey ID: {$surveyId}");
        $this->info("📝 Survey: {$survey->name}");
        $this->info("🏢 Master Survey: {$survey->masterSurvey?->name}");
        $this->newLine();

        // Check prerequisites
        $this->line("📋 Prerequisites Check:");
        
        if (!$survey->is_scored) {
            $this->error("   ❌ Survey not finalized (is_scored = false)");
            $this->warn("   → Please finalize survey first!");
            return 1;
        }
        $this->info("   ✅ Survey is finalized");

        $txCount = $survey->transaction()->count();
        if ($txCount === 0) {
            $this->error("   ❌ No transactions found");
            return 1;
        }
        $this->info("   ✅ Has {$txCount} transactions");

        $nilaiCount = $survey->transaction()
            ->whereHas('nilai')
            ->count();
        
        if ($nilaiCount < $txCount) {
            $this->error("   ❌ Missing nilai: {$nilaiCount}/{$txCount}");
            return 1;
        }
        $this->info("   ✅ All transactions have nilai");
        
        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // Step 1: Sync to PostgreSQL
        $this->newLine();
        $this->info("📤 Step 1: Syncing data to PostgreSQL...");
        
        $syncResult = $this->mlService->syncSurveyDataToPostgres($surveyId);
        
        if (!$syncResult['success']) {
            $this->error("❌ Sync failed!");
            $this->error("   Message: {$syncResult['message']}");
            $this->newLine();
            $this->warn("💡 Check logs:");
            $this->line("   tail -f storage/logs/laravel.log");
            return 1;
        }

        $this->info("✅ Sync successful!");
        $this->line("   Records synced: {$syncResult['records_synced']}");
        $this->line("   Synced at: {$syncResult['synced_at']}");
        
        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // Step 2: Trigger Airflow DAG
        $this->newLine();
        $this->info("🚀 Step 2: Triggering ML retraining (Airflow DAG)...");
        
        $retrainResult = $this->mlService->triggerRetraining($surveyId);
        
        if (!$retrainResult['success']) {
            $this->error("❌ Trigger failed!");
            $this->error("   Message: {$retrainResult['message']}");
            $this->newLine();
            $this->warn("💡 Possible issues:");
            $this->line("   • Airflow not running: docker ps | grep airflow");
            $this->line("   • DAG not found: Check http://localhost:8080");
            $this->line("   • Authentication issue: Check Airflow credentials");
            return 1;
        }

        $this->info("✅ ML retraining triggered!");
        $this->line("   DAG Run ID: {$retrainResult['dag_run_id']}");
        $this->line("   Message: {$retrainResult['message']}");
        
        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // Step 3: Monitor status
        $this->newLine();
        $this->info("⏳ Step 3: Checking DAG status...");
        $this->line("   Waiting 3 seconds for DAG to start...");
        sleep(3);

        $statusResult = $this->mlService->checkRetrainingStatus($retrainResult['dag_run_id']);
        
        if ($statusResult['success']) {
            $state = $statusResult['state'] ?? 'unknown';
            $stateEmoji = match($state) {
                'running' => '🔄',
                'success' => '✅',
                'failed' => '❌',
                default => '⏳'
            };
            
            $this->line("   {$stateEmoji} DAG State: {$state}");
        } else {
            $this->warn("   ⚠️ Could not check status: {$statusResult['message']}");
        }

        $this->newLine();
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();
        
        $this->info("🎉 Test Complete!");
        $this->newLine();
        $this->info("📊 Monitor progress:");
        $this->line("   • Airflow UI: http://localhost:8080");
        $this->line("   • DAG: master_mitra_survey");
        $this->line("   • Run ID: {$retrainResult['dag_run_id']}");
        $this->newLine();
        $this->line("⏱️ Estimated completion: 45-60 seconds");

        return 0;
    }
}
