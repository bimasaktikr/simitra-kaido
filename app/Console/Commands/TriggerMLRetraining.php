<?php

namespace App\Console\Commands;

use App\Models\Survey;
use App\Services\MLRecommendationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TriggerMLRetraining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:trigger 
                            {surveyId? : Survey ID to trigger}
                            {--all : Trigger all ready surveys}
                            {--force : Force trigger even if already synced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually trigger ML retraining for finalized surveys';

    /**
     * ML Recommendation Service
     */
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
        $triggerAll = $this->option('all');
        $force = $this->option('force');

        if ($triggerAll) {
            return $this->triggerAllReadySurveys($force);
        }

        if (!$surveyId) {
            $this->error("❌ Please provide a survey ID or use --all flag");
            $this->line("Examples:");
            $this->line("  php artisan ml:trigger 61");
            $this->line("  php artisan ml:trigger --all");
            return 1;
        }

        return $this->triggerSurvey($surveyId, $force);
    }

    /**
     * Trigger ML retraining for a specific survey
     */
    protected function triggerSurvey(int $surveyId, bool $force = false): int
    {
        $this->info("🔄 Triggering ML retraining for Survey #{$surveyId}");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        // Fetch survey data
        $survey = Survey::with(['masterSurvey', 'transaction.nilai'])->find($surveyId);

        if (!$survey) {
            $this->error("❌ Survey #{$surveyId} not found!");
            return 1;
        }

        // Check if already synced
        if ($survey->is_synced && !$force) {
            $this->warn("⚠️  Survey #{$surveyId} has already been triggered!");
            $this->line("   Last synced: {$survey->updated_at}");
            $this->newLine();
            $this->line("💡 Use --force flag to trigger again:");
            $this->line("   php artisan ml:trigger {$surveyId} --force");
            return 0;
        }

        // Validate survey is ready
        $validation = $this->validateSurvey($survey);
        if (!$validation['ready']) {
            $this->error("❌ Survey #{$surveyId} is NOT ready for ML trigger!");
            $this->newLine();
            $this->warn("⚠️  Issues:");
            foreach ($validation['errors'] as $error) {
                $this->line("   • {$error}");
            }
            return 1;
        }

        // Show survey info
        $this->info("📋 Survey Information:");
        $this->line("   ID: {$survey->id}");
        $this->line("   Master Survey: " . ($survey->masterSurvey->name ?? 'N/A'));
        $this->line("   Type: " . ($survey->masterSurvey->type ?? 'N/A'));
        $this->line("   Status: {$survey->status}");
        $this->line("   Mitras: {$validation['stats']['total_mitras']}");
        $this->line("   Nilai: {$validation['stats']['total_nilai']}");
        $this->newLine();

        // Confirm before proceeding
        if (!$force && !$this->confirm('🚀 Proceed with ML retraining?', true)) {
            $this->warn("⏸️  Trigger cancelled.");
            return 0;
        }

        // Step 0: Sync master survey types from ML backend
        $this->line("🔄 Step 0: Syncing master survey types from ML backend...");
        try {
            \Artisan::call('ml:sync-master-survey-types');
            $this->info("✅ Master survey types synced");
        } catch (\Exception $e) {
            $this->warn("⚠️ Could not sync master survey types: " . $e->getMessage());
        }
        $this->newLine();

        // Step 1: Sync to PostgreSQL
        $this->line("📤 Step 1: Syncing survey data to PostgreSQL...");
        $syncResult = $this->syncToPostgres($surveyId);

        if (!$syncResult['success']) {
            $this->error("❌ Failed to sync to PostgreSQL!");
            $this->line("   Error: {$syncResult['message']}");
            return 1;
        }

        $this->info("   ✅ Synced {$syncResult['records']} records");
        $this->newLine();

        // Step 2: Trigger Airflow DAG
        $this->line("🚀 Step 2: Triggering Airflow DAG...");
        $dagResult = $this->triggerAirflowDAG($surveyId);

        if (!$dagResult['success']) {
            $this->error("❌ Failed to trigger Airflow DAG!");
            $this->line("   Error: {$dagResult['message']}");
            $this->warn("   Note: Data was synced to PostgreSQL but DAG didn't trigger.");
            $this->line("   You can manually trigger from Airflow UI: http://localhost:8080");
            return 1;
        }

        $this->info("   ✅ DAG triggered successfully!");
        $this->line("   DAG ID: master_mitra_survey");
        $this->line("   Run ID: " . ($dagResult['run_id'] ?? 'N/A'));
        $this->newLine();

        // Step 3: Wait for DAG completion (auto cache refresh via webhook)
        $this->line("⏳ Step 3: Waiting for DAG completion...");
        $this->line("   ℹ️  DAG will automatically refresh cache when completed");
        $this->line("   ℹ️  Airflow will notify Laravel via webhook after training");
        $this->newLine();

        // Update survey is_synced flag
        $survey->update(['is_synced' => 1]);

        // Success summary
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ ML RETRAINING TRIGGERED SUCCESSFULLY!");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();
        $this->line("📊 Next Steps:");
        $this->line("   1. Airflow DAG is now running (takes ~1-2 minutes)");
        $this->line("   2. PSO algorithm will optimize with new data");
        $this->line("   3. Recommendations will be updated");
        $this->newLine();
        $this->line("🔍 Monitor progress:");
        $this->line("   • Airflow UI: http://localhost:8080");
        $this->line("   • FastAPI Docs: http://localhost:8001/docs");
        $this->newLine();
        $this->line("📈 Check results:");
        $this->line("   curl http://localhost:8001/rekomendasi/rumah_tangga?limit=5");

        return 0;
    }

    /**
     * Trigger all ready surveys
     */
    protected function triggerAllReadySurveys(bool $force = false): int
    {
        $this->info("🔄 Triggering ML retraining for ALL ready surveys");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        // Find all ready surveys
        $query = Survey::with(['masterSurvey', 'transaction.nilai'])
            ->where('is_scored', 1);

        if (!$force) {
            $query->where('is_synced', 0);
        }

        $surveys = $query->orderBy('id', 'asc')->get();

        // Filter surveys that are truly ready
        $readySurveys = $surveys->filter(function ($survey) {
            $validation = $this->validateSurvey($survey);
            return $validation['ready'];
        });

        if ($readySurveys->isEmpty()) {
            $this->info("✅ No surveys need to be triggered!");
            $this->line("   All finalized surveys have been synced.");
            return 0;
        }

        $this->info("📋 Found {$readySurveys->count()} survey(s) ready for ML trigger:");
        $this->newLine();

        $tableData = [];
        foreach ($readySurveys as $survey) {
            $tableData[] = [
                $survey->id,
                $survey->masterSurvey->name ?? 'N/A',
                $survey->masterSurvey->type ?? 'N/A',
                $survey->transaction()->count(),
            ];
        }

        $this->table(['Survey ID', 'Master Survey', 'Type', 'Mitras'], $tableData);
        $this->newLine();

        // Confirm before proceeding
        if (!$force && !$this->confirm('🚀 Proceed with triggering all surveys?', true)) {
            $this->warn("⏸️  Trigger cancelled.");
            return 0;
        }

        // Trigger each survey
        $successCount = 0;
        $failCount = 0;

        foreach ($readySurveys as $survey) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("Processing Survey #{$survey->id}...");
            $this->newLine();

            $result = $this->triggerSurvey($survey->id, true);
            
            if ($result === 0) {
                $successCount++;
            } else {
                $failCount++;
            }

            $this->newLine();
        }

        // Final summary
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📊 BATCH TRIGGER COMPLETE!");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("   ✅ Successful: {$successCount}");
        if ($failCount > 0) {
            $this->line("   ❌ Failed: {$failCount}");
        }
        $this->newLine();

        // Since we triggered multiple surveys, use the last successful survey ID for DAG trigger
        if ($successCount > 0) {
            $this->info("🚀 Triggering Airflow DAG for batch retraining...");
            // Use last synced survey ID
            $lastSurveyId = $readySurveys->last()->id;
            $dagResult = $this->triggerAirflowDAG($lastSurveyId);
            
            if ($dagResult['success']) {
                $this->info("   ✅ DAG triggered successfully!");
            } else {
                $this->error("   ❌ Failed to trigger DAG: {$dagResult['message']}");
            }
        }

        return $failCount > 0 ? 1 : 0;
    }

    /**
     * Validate if survey is ready for ML trigger
     */
    protected function validateSurvey(Survey $survey): array
    {
        $errors = [];
        $stats = [
            'total_mitras' => 0,
            'total_nilai' => 0,
        ];

        // Check master survey
        if (!$survey->masterSurvey) {
            $errors[] = "No master survey linked";
        }

        // Check transactions
        $totalTx = $survey->transaction()->count();
        $stats['total_mitras'] = $totalTx;

        if ($totalTx === 0) {
            $errors[] = "No mitras assigned to this survey";
        }

        // Check nilai
        $nilaiCount = DB::table('nilai1s')
            ->whereIn('transaction_id', $survey->transaction()->pluck('id'))
            ->count();
        
        $stats['total_nilai'] = $nilaiCount;

        if ($totalTx > 0 && $nilaiCount < $totalTx) {
            $errors[] = "Missing nilai for " . ($totalTx - $nilaiCount) . " mitra(s)";
        }

        // Check finalized
        if (!$survey->is_scored) {
            $errors[] = "Survey not finalized (is_scored = 0)";
        }

        return [
            'ready' => empty($errors),
            'errors' => $errors,
            'stats' => $stats,
        ];
    }

    /**
     * Sync survey data to PostgreSQL
     */
    protected function syncToPostgres(int $surveyId): array
    {
        try {
            $result = $this->mlService->syncSurveyDataToPostgres($surveyId);
            
            return [
                'success' => $result['success'],
                'records' => $result['records_synced'] ?? 0,
                'message' => $result['message'] ?? 'Sync completed',
            ];
        } catch (\Exception $e) {
            Log::error("Failed to sync survey {$surveyId} to PostgreSQL", [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'records' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Trigger Airflow DAG
     */
    protected function triggerAirflowDAG(int $surveyId): array
    {
        try {
            $result = $this->mlService->triggerRetraining($surveyId);
            
            return [
                'success' => $result['success'],
                'run_id' => $result['dag_run_id'] ?? null,
                'message' => $result['message'] ?? 'DAG triggered',
            ];
        } catch (\Exception $e) {
            Log::error("Failed to trigger Airflow DAG", [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
