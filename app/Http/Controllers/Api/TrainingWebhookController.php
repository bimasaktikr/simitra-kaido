<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MLRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrainingWebhookController extends Controller
{
    protected MLRecommendationService $mlService;

    public function __construct(MLRecommendationService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Webhook endpoint yang dipanggil oleh FastAPI setelah Airflow training selesai.
     * 
     * Flow:
     * 1. Airflow selesai training → simpan ke PostgreSQL
     * 2. Airflow POST ke FastAPI /webhooks/training-complete
     * 3. FastAPI POST ke endpoint Laravel ini
     * 4. Laravel refresh MySQL cache dari PostgreSQL (via FastAPI)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleTrainingComplete(Request $request)
    {
        try {
            $surveyType = $request->input('survey_type');
            $source = $request->input('source', 'unknown');
            
            Log::info("🔔 Received training complete webhook", [
                'survey_type' => $surveyType,
                'source' => $source,
                'timestamp' => now()->toIso8601String()
            ]);

            // Refresh cache untuk survey type yang di-training
            // Jika survey_type null, refresh semua
            if ($surveyType) {
                $result = $this->mlService->refreshCache($surveyType, 9999);
                
                Log::info("✅ Cache refreshed for survey type", [
                    'survey_type' => $surveyType,
                    'count' => $result['total'] ?? 0
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => "Cache refreshed for {$surveyType}",
                    'survey_type' => $surveyType,
                    'records_cached' => $result['total'] ?? 0
                ]);
            } else {
                // Refresh both survey types
                $resultRT = $this->mlService->refreshCache('Rumah Tangga', 9999);
                $resultP = $this->mlService->refreshCache('Perusahaan', 9999);
                
                Log::info("✅ Cache refreshed for all survey types", [
                    'rumah_tangga' => $resultRT['total'] ?? 0,
                    'perusahaan' => $resultP['total'] ?? 0
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cache refreshed for all survey types',
                    'records_cached' => [
                        'rumah_tangga' => $resultRT['total'] ?? 0,
                        'perusahaan' => $resultP['total'] ?? 0,
                        'total' => ($resultRT['total'] ?? 0) + ($resultP['total'] ?? 0)
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error("❌ Error handling training webhook", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to refresh cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Health check untuk webhook endpoint
     */
    public function healthCheck()
    {
        return response()->json([
            'status' => 'healthy',
            'service' => 'Laravel Training Webhook',
            'endpoints' => [
                '/api/webhooks/training-complete' => 'POST - Receive training complete notification (from FastAPI)',
                '/api/webhooks/ml-training-complete' => 'POST - Receive ML training complete notification (from Airflow)'
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Webhook endpoint yang dipanggil LANGSUNG oleh Airflow setelah DAG selesai.
     * 
     * Flow:
     * 1. Airflow DAG selesai training → simpan ke PostgreSQL
     * 2. Airflow POST LANGSUNG ke endpoint Laravel ini (last task in DAG)
     * 3. Laravel refresh MySQL cache dari PostgreSQL (via FastAPI)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleMLTrainingComplete(Request $request)
    {
        try {
            $dagRunId = $request->input('dag_run_id', 'unknown');
            $status = $request->input('status', 'unknown');
            $message = $request->input('message', '');
            
            Log::info("🔔 Received ML training complete webhook from Airflow", [
                'dag_run_id' => $dagRunId,
                'status' => $status,
                'message' => $message,
                'timestamp' => now()->toIso8601String()
            ]);

            if ($status !== 'success') {
                Log::warning("⚠️ Training status is not success: {$status}");
                return response()->json([
                    'status' => 'warning',
                    'message' => "Training status was: {$status}. Cache not refreshed."
                ], 200);
            }

            // Refresh cache untuk SEMUA survey types (karena DAG process semua)
            $resultRT = $this->mlService->refreshCache('Rumah Tangga', 9999);
            $resultP = $this->mlService->refreshCache('Perusahaan', 9999);
            
            Log::info("✅ Cache refreshed after Airflow completion", [
                'dag_run_id' => $dagRunId,
                'rumah_tangga' => $resultRT['total'] ?? 0,
                'perusahaan' => $resultP['total'] ?? 0
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'ML training completed and cache refreshed successfully',
                'dag_run_id' => $dagRunId,
                'records_cached' => [
                    'rumah_tangga' => $resultRT['total'] ?? 0,
                    'perusahaan' => $resultP['total'] ?? 0,
                    'total' => ($resultRT['total'] ?? 0) + ($resultP['total'] ?? 0)
                ],
                'timestamp' => now()->toIso8601String()
            ]);
            
        } catch (\Exception $e) {
            Log::error("❌ Error handling ML training webhook from Airflow", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to refresh cache: ' . $e->getMessage()
            ], 500);
        }
    }
}
