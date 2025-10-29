<?php

namespace App\Services;

use App\Models\MLRecommendationCache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
     * Uses MySQL cache if available, otherwise fetches from ML API
     * 
     * @param string $surveyType 'Rumah Tangga' or 'Perusahaan'
     * @param int $limit Number of recommendations to return
     * @param bool $forceRefresh Force fetch from API even if cache exists
     * @return array
     */
    public function getRecommendations(string $surveyType, int $limit = 10, bool $forceRefresh = false): array
    {
        try {
            Log::info("🤖 Fetching ML recommendations", [
                'survey_type' => $surveyType,
                'limit' => $limit,
                'force_refresh' => $forceRefresh
            ]);

            // Check MySQL cache first (unless force refresh)
            if (!$forceRefresh) {
                $cachedRecommendations = $this->getFromCache($surveyType, $limit);
                
                if ($cachedRecommendations['success']) {
                    Log::info("✅ Using cached recommendations from MySQL", [
                        'survey_type' => $surveyType,
                        'count' => count($cachedRecommendations['data']),
                        'cached_at' => $cachedRecommendations['cached_at'] ?? null
                    ]);
                    
                    return $cachedRecommendations;
                }
            }

            // Fetch from ML API if no cache or force refresh
            Log::info("📡 Fetching fresh data from ML API", [
                'survey_type' => $surveyType,
                'reason' => $forceRefresh ? 'force_refresh' : 'no_cache'
            ]);

            $apiRecommendations = $this->fetchFromMLAPI($surveyType, $limit);

            if ($apiRecommendations['success']) {
                // Cache to MySQL for future requests
                $this->cacheToMySQL($surveyType, $apiRecommendations['data']);
                
                Log::info("✅ Cached fresh recommendations to MySQL", [
                    'survey_type' => $surveyType,
                    'count' => count($apiRecommendations['data'])
                ]);
            }

            return $apiRecommendations;

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
     * Get recommendations from MySQL cache
     */
    protected function getFromCache(string $surveyType, int $limit): array
    {
        // Check if cache exists and is not stale
        if (MLRecommendationCache::isCacheStale($surveyType)) {
            Log::info("⚠️  Cache is stale or missing", ['survey_type' => $surveyType]);
            return ['success' => false, 'data' => []];
        }

        $cached = MLRecommendationCache::forSurveyType($surveyType)
            ->orderBy('final_rank_score', 'desc')
            ->limit($limit)
            ->get();

        if ($cached->isEmpty()) {
            return ['success' => false, 'data' => []];
        }

        $recommendations = $cached->map(function($item) use ($surveyType) {
            return [
                'mitra_id' => $item->mitra_id,
                'mitra_name' => $item->mitra_name,
                'optimized_score' => $item->optimized_score,
                'final_rank_score' => $item->final_rank_score,
                'survey_score' => $item->survey_score,
                'jumlah_survey' => $item->jumlah_survey,
                'exp_norm' => $item->exp_norm,
                'weighted_score' => $item->weighted_score,
                'survey_type' => $surveyType,
                'created_at' => $item->created_at
            ];
        })->toArray();

        return [
            'success' => true,
            'data' => $recommendations,
            'total' => count($recommendations),
            'survey_type' => $surveyType,
            'source' => 'mysql_cache',
            'cached_at' => $cached->first()->cached_at->toIso8601String(),
            'message' => 'Recommendations from MySQL cache'
        ];
    }

    /**
     * Fetch recommendations from ML API
     * Note: No limit applied - fetches ALL recommendations for caching
     */
    protected function fetchFromMLAPI(string $surveyType, int $limit): array
    {
        // Map survey type to endpoint
        $endpoint = $surveyType === 'Rumah Tangga' ? '/rekomendasi/rumah_tangga' : '/rekomendasi/perusahaan';
        
        Log::info("🔄 Mapping survey type to endpoint", [
            'survey_type' => $surveyType,
            'endpoint' => $endpoint
        ]);
        
        $url = "{$this->apiUrl}{$endpoint}";
        // No limit parameter - fetch ALL recommendations for complete caching
        $params = [];
        
        Log::info("📡 Calling ML API (fetching ALL recommendations)", [
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
                ->map(function($item, $index) {
                    return [
                        'mitra_id' => (int) $item['mitra_id'],
                        'mitra_name' => $item['mitra_name'] ?? null,
                        'mitra_email' => $item['mitra_email'] ?? null,
                        'pendidikan' => $item['pendidikan'] ?? null,
                        'jenis_kelamin' => $item['jenis_kelamin'] ?? null,
                        'optimized_score' => (float) ($item['optimized_score'] ?? 0),
                        'final_rank_score' => (float) ($item['final_rank_score'] ?? 0),
                        'survey_score' => (float) ($item['survey_score'] ?? 0),
                        'jumlah_survey' => (int) ($item['jumlah_survey'] ?? 0),
                        'exp_norm' => (float) ($item['exp_norm'] ?? 0),
                        'weighted_score' => (float) ($item['weighted_score'] ?? 0),
                        'survey_type' => $item['survey_type'] ?? null,
                        'rank' => $index + 1 // Add ranking based on order
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
                'source' => 'ml_api',
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
    }

    /**
     * Cache recommendations to MySQL
     */
    /**
     * Cache recommendations to MySQL
     * Uses separate tables for each survey type
     */
    protected function cacheToMySQL(string $surveyType, array $recommendations): void
    {
        try {
            Log::info("🔄 Starting cache to MySQL", [
                'survey_type' => $surveyType,
                'count' => count($recommendations)
            ]);
            
            DB::beginTransaction();

            // Clear old cache for this survey type using dynamic model
            $deleted = MLRecommendationCache::clearCache($surveyType);
            Log::info("🗑️ Cleared old cache", [
                'survey_type' => $surveyType,
                'records_deleted' => $deleted
            ]);

            // Prepare bulk insert data - HANYA 10 field sesuai API
            $now = now();
            $insertData = [];
            
            foreach ($recommendations as $recommendation) {
                $insertData[] = [
                    'mitra_id' => $recommendation['mitra_id'],
                    'mitra_name' => $recommendation['mitra_name'],
                    'survey_score' => $recommendation['survey_score'],
                    'jumlah_survey' => $recommendation['jumlah_survey'],
                    'exp_norm' => $recommendation['exp_norm'],
                    'weighted_score' => $recommendation['weighted_score'],
                    'optimized_score' => $recommendation['optimized_score'],
                    'final_rank_score' => $recommendation['final_rank_score'],
                    'cached_at' => $now,
                    'created_at' => $recommendation['created_at'] ?? $now,
                    'updated_at' => $now,
                ];
            }

            // Bulk insert using forSurveyType for dynamic table selection
            MLRecommendationCache::forSurveyType($surveyType)->insert($insertData);

            DB::commit();

            Log::info("✅ Successfully cached {count} recommendations to MySQL", [
                'count' => count($insertData),
                'survey_type' => $surveyType
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("❌ Failed to cache recommendations to MySQL", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'survey_type' => $surveyType
            ]);
            throw $e; // Re-throw so caller knows it failed
        }
    }

    /**
     * Refresh cache for a survey type (force fetch from API)
     */
    public function refreshCache(string $surveyType, int $limit = 100): array
    {
        Log::info("🔄 Refreshing cache for survey type", [
            'survey_type' => $surveyType,
            'limit' => $limit
        ]);

        return $this->getRecommendations($surveyType, $limit, true);
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
