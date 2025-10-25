<?php

namespace App\Observers;

use App\Models\Survey;
use App\Services\MLRecommendationService;
use Illuminate\Support\Facades\Log;

class SurveyObserver
{
    protected MLRecommendationService $mlService;

    public function __construct(MLRecommendationService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Handle the Survey "updated" event.
     * Auto-trigger ML retraining when survey is finalized
     */
    public function updated(Survey $survey): void
    {
        // Check if is_scored changed from 0 to 1 (finalized)
        if ($survey->isDirty('is_scored') && $survey->is_scored == 1) {
            Log::info("🎯 Survey finalized, auto-triggering ML retraining", [
                'survey_id' => $survey->id,
                'master_survey_id' => $survey->master_survey_id,
                'transactions_count' => $survey->transaction()->count(),
            ]);

            // Step 1: Sync survey data to PostgreSQL
            $syncResult = $this->mlService->syncSurveyDataToPostgres($survey->id);

            if ($syncResult['success']) {
                Log::info("✅ Survey data synced to PostgreSQL", [
                    'survey_id' => $survey->id,
                    'records_synced' => $syncResult['records_synced'] ?? 0,
                ]);

                // Mark as synced (use updateQuietly to avoid triggering observer again)
                $survey->updateQuietly(['is_synced' => 1]);

                // Step 2: Trigger Airflow DAG for ML retraining
                $retrainingResult = $this->mlService->triggerRetraining($survey->id);

                if ($retrainingResult['success']) {
                    Log::info("✅ ML retraining triggered successfully", [
                        'survey_id' => $survey->id,
                        'dag_run_id' => $retrainingResult['dag_run_id'] ?? null,
                    ]);
                } else {
                    Log::error("❌ Failed to trigger ML retraining", [
                        'survey_id' => $survey->id,
                        'error' => $retrainingResult['message'] ?? 'Unknown error',
                    ]);
                }
            } else {
                Log::error("❌ Failed to sync survey data to PostgreSQL", [
                    'survey_id' => $survey->id,
                    'error' => $syncResult['message'] ?? 'Unknown error',
                ]);
            }
        }
    }

    /**
     * Handle the Survey "created" event.
     */
    public function created(Survey $survey): void
    {
        Log::info("📝 New survey created", [
            'survey_id' => $survey->id,
            'master_survey_id' => $survey->master_survey_id,
        ]);
    }

    /**
     * Handle the Survey "deleted" event.
     */
    public function deleted(Survey $survey): void
    {
        Log::info("🗑️ Survey deleted", [
            'survey_id' => $survey->id,
        ]);
    }
}
