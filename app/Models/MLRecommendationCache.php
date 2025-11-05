<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class MLRecommendationCache extends Model
{
    // Dynamic table name based on survey type
    protected $table = 'ml_cache_rumah_tangga'; // Default

    protected $fillable = [
        'mitra_id',
        'mitra_name',
        'survey_score',
        'jumlah_survey',
        'exp_norm',
        'weighted_score',
        'optimized_score',
        'final_rank_score',
        'cached_at',
    ];

    protected $casts = [
        'survey_score' => 'float',
        'jumlah_survey' => 'integer',
        'exp_norm' => 'float',
        'weighted_score' => 'float',
        'optimized_score' => 'float',
        'final_rank_score' => 'float',
        'cached_at' => 'datetime',
    ];

    /**
     * Set table dynamically based on survey type
     */
    public function setTableForSurveyType(string $surveyType): self
    {
        $this->table = $surveyType === 'Rumah Tangga' 
            ? 'ml_cache_rumah_tangga' 
            : 'ml_cache_perusahaan';
        
        return $this;
    }

    /**
     * Get instance with specific table
     */
    public static function forSurveyType(string $surveyType): self
    {
        $instance = new static;
        $instance->setTableForSurveyType($surveyType);
        return $instance;
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    /**
     * Get top N recommendations ordered by ML score
     */
    public static function getTopRecommendations(string $surveyType, int $limit = 10)
    {
        return static::forSurveyType($surveyType)
            ->orderBy('final_rank_score', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if cache is stale (older than 24 hours)
     */
    public static function isCacheStale(string $surveyType, int $hoursThreshold = 24): bool
    {
        $latestCache = static::forSurveyType($surveyType)
            ->latest('cached_at')
            ->first();
        
        if (!$latestCache) {
            return true; // No cache = stale
        }
        
        return $latestCache->cached_at->diffInHours(now()) > $hoursThreshold;
    }

    /**
     * Clear old cache for specific survey type
     */
    public static function clearCache(string $surveyType): int
    {
        $query = static::forSurveyType($surveyType)->newQuery();
        $deleted = $query->delete() ?? 0;
        
        Log::info("🗑️ Cleared ML recommendation cache", [
            'survey_type' => $surveyType,
            'records_deleted' => $deleted
        ]);
        
        return $deleted;
    }

    /**
     * Get cache statistics for monitoring
     */
    public static function getCacheStats(): array
    {
        $rumahTanggaCount = static::forSurveyType('Rumah Tangga')->count();
        $perusahaanCount = static::forSurveyType('Perusahaan')->count();
        
        $latestRumahTangga = static::forSurveyType('Rumah Tangga')
            ->latest('cached_at')
            ->first();
        
        $latestPerusahaan = static::forSurveyType('Perusahaan')
            ->latest('cached_at')
            ->first();

        return [
            'total_cached' => $rumahTanggaCount + $perusahaanCount,
            'rumah_tangga' => [
                'count' => $rumahTanggaCount,
                'cached_at' => $latestRumahTangga?->cached_at?->toIso8601String(),
                'is_stale' => static::isCacheStale('Rumah Tangga')
            ],
            'perusahaan' => [
                'count' => $perusahaanCount,
                'cached_at' => $latestPerusahaan?->cached_at?->toIso8601String(),
                'is_stale' => static::isCacheStale('Perusahaan')
            ]
        ];
    }
}
