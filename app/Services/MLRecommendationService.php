<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MLRecommendationService
{
    protected $apiUrl;
    protected $airflowUrl;

    public function __construct()
    {
        $this->apiUrl = config('api-service.ml_api_url', 'http://localhost:8001');
        $this->airflowUrl = config('api-service.airflow_url', 'http://localhost:8080/api/v1');
    }

    /**
     * Get mitra recommendations based on survey type
     * 
     * @param string $surveyType 'Rumah Tangga' or 'Perusahaan'
     * @param int $limit Number of recommendations to return
     * @return array
     */
    public function getRecommendations(string $surveyType, int $limit = 10): array
    {
        try {
            Log::info("🤖 Fetching ML recommendations", [
                'survey_type' => $surveyType,
                'limit' => $limit
            ]);

            // Map survey type to endpoint
            // Use specific endpoints that have full parameters (survey_score, jumlah_survey, etc)
            $endpoint = $surveyType === 'Rumah Tangga' ? '/rekomendasi/rumah_tangga' : '/rekomendasi/perusahaan';
            
            Log::info("🔄 Mapping survey type to endpoint", [
                'survey_type' => $surveyType,
                'endpoint' => $endpoint
            ]);
            
            $url = "{$this->apiUrl}{$endpoint}";
            $params = ['limit' => $limit];
            
            Log::info("📡 Calling ML API", [
                'url' => $url,
                'params' => $params
            ]);
            
            $response = Http::timeout(30)->get($url, $params);
            
            Log::info("📥 API Response", [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body_preview' => substr($response->body(), 0, 200)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Parse API response structure with full parameters
                $recommendations = collect($data['data'] ?? [])
                    ->map(function($item) {
                        return [
                            'mitra_id' => (int) $item['mitra_id'],
                            'mitra_name' => $item['mitra_name'] ?? null,
                            'mitra_email' => $item['mitra_email'] ?? null,
                            'pendidikan' => $item['pendidikan'] ?? null,
                            'jenis_kelamin' => $item['jenis_kelamin'] ?? null,
                            'optimized_score' => (float) ($item['optimized_score'] ?? 0), // For Rating Mitra
                            'final_rank_score' => (float) ($item['final_rank_score'] ?? 0), // For ML Score
                            'survey_score' => (float) ($item['survey_score'] ?? 0), // Average rating dari survey
                            'jumlah_survey' => (int) ($item['jumlah_survey'] ?? 0), // Total survey count
                            'exp_norm' => (float) ($item['exp_norm'] ?? 0),
                            'weighted_score' => (float) ($item['weighted_score'] ?? 0),
                            'survey_type' => $item['survey_type'] ?? null
                        ];
                    })
                    ->toArray();
                
                Log::info("✅ ML recommendations received", [
                    'count' => count($recommendations),
                    'survey_type' => $surveyType,
                    'top_3' => array_slice(array_map(fn($r) => [
                        'mitra_id' => $r['mitra_id'],
                        'final_rank_score' => $r['final_rank_score'],
                        'optimized_score' => $r['optimized_score'],
                        'survey_score' => $r['survey_score'],
                        'jumlah_survey' => $r['jumlah_survey']
                    ], $recommendations), 0, 3)
                ]);

                return [
                    'success' => true,
                    'data' => $recommendations,
                    'total' => count($recommendations),
                    'survey_type' => $surveyType,
                    'message' => 'Recommendations fetched successfully'
                ];
            }

            Log::error("❌ ML API error", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to fetch recommendations: ' . $response->body()
            ];

        } catch (Exception $e) {
            Log::error("❌ ML Recommendation error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'data' => [],
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Trigger ML retraining after survey finalization
     * 
     * @param int $surveyId Survey that was finalized
     * @return array
     */
    public function triggerRetraining(int $surveyId): array
    {
        try {
            $url = "{$this->airflowUrl}/dags/master_mitra_survey/dagRuns";
            
            Log::info("🔄 Triggering ML retraining", [
                'survey_id' => $surveyId,
                'url' => $url
            ]);

            // Trigger Airflow DAG for retraining
            // Note: $this->airflowUrl already includes /api/v1
            $response = Http::timeout(30)
                ->acceptJson()
                ->contentType('application/json')
                ->post($url, [
                    'conf' => [
                        'triggered_by' => 'survey_finalization',
                        'survey_id' => $surveyId,
                        'timestamp' => now()->toIso8601String()
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info("✅ ML retraining triggered", [
                    'dag_run_id' => $data['dag_run_id'] ?? null
                ]);

                return [
                    'success' => true,
                    'dag_run_id' => $data['dag_run_id'] ?? null,
                    'message' => 'ML retraining triggered successfully'
                ];
            }

            Log::error("❌ Airflow trigger error", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to trigger retraining: ' . $response->body()
            ];

        } catch (Exception $e) {
            Log::error("❌ ML Retraining trigger error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check ML retraining status
     * 
     * @param string $dagRunId DAG run ID from trigger
     * @return array
     */
    public function checkRetrainingStatus(string $dagRunId): array
    {
        try {
            $response = Http::timeout(10)
                ->withBasicAuth('', '')
                ->get("{$this->airflowUrl}/dags/master_mitra_survey/dagRuns/{$dagRunId}");

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'state' => $data['state'] ?? 'unknown',
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to check status'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sync finalized survey data to PostgreSQL for ML training
     * 
     * @param int $surveyId Survey that was finalized
     * @return array
     */
    public function syncSurveyDataToPostgres(int $surveyId): array
    {
        try {
            Log::info("📤 Syncing survey data to PostgreSQL", [
                'survey_id' => $surveyId,
                'api_url' => $this->apiUrl . '/sync/survey'
            ]);

            // Call API endpoint to sync data
            $response = Http::timeout(30)
                ->post("{$this->apiUrl}/sync/survey", [
                    'survey_id' => $surveyId,
                    'source' => 'laravel_finalization'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info("✅ Survey data synced to PostgreSQL", [
                    'records_synced' => $data['records_synced'] ?? 0,
                    'synced_at' => $data['synced_at'] ?? null
                ]);

                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'Survey data synced successfully',
                    'records_synced' => $data['records_synced'] ?? 0,
                    'synced_at' => $data['synced_at'] ?? null
                ];
            }

            Log::error("❌ Sync error", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to sync data: ' . $response->body()
            ];

        } catch (Exception $e) {
            Log::error("❌ Sync error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
