<?php

/**
 * Performance Benchmark Script
 * Tests ML recommendation caching performance
 * 
 * Usage: php tests/ml_cache_benchmark.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\MLRecommendationService;
use App\Models\MLRecommendationCache;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  ML Recommendation Cache - Performance Benchmark              ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$service = app(MLRecommendationService::class);
$surveyType = 'Rumah Tangga';
$limit = 50;

// Step 1: Clear cache for fresh test
echo "🧹 Clearing existing cache...\n";
MLRecommendationCache::clearCache($surveyType);
echo "   ✅ Cache cleared\n\n";

// Step 2: First call - should fetch from API and cache
echo "📡 Call 1: Fetching from ML API (will cache)...\n";
$start1 = microtime(true);
$result1 = $service->getRecommendations($surveyType, $limit);
$time1 = (microtime(true) - $start1) * 1000; // Convert to milliseconds

if ($result1['success']) {
    echo "   ✅ Success!\n";
    echo "   Source: {$result1['source']}\n";
    echo "   Count: {$result1['total']} recommendations\n";
    echo "   Time: " . round($time1, 2) . " ms\n";
} else {
    echo "   ❌ Failed: {$result1['message']}\n";
    exit(1);
}

echo "\n";

// Step 3: Second call - should hit cache
sleep(1); // Small delay
echo "⚡ Call 2: Reading from MySQL cache...\n";
$start2 = microtime(true);
$result2 = $service->getRecommendations($surveyType, $limit);
$time2 = (microtime(true) - $start2) * 1000;

if ($result2['success']) {
    echo "   ✅ Success!\n";
    echo "   Source: {$result2['source']}\n";
    echo "   Count: {$result2['total']} recommendations\n";
    echo "   Time: " . round($time2, 2) . " ms\n";
} else {
    echo "   ❌ Failed: {$result2['message']}\n";
}

echo "\n";

// Step 4: Third call - should hit cache again
sleep(1); // Small delay
echo "⚡ Call 3: Reading from MySQL cache (again)...\n";
$start3 = microtime(true);
$result3 = $service->getRecommendations($surveyType, $limit);
$time3 = (microtime(true) - $start3) * 1000;

if ($result3['success']) {
    echo "   ✅ Success!\n";
    echo "   Source: {$result3['source']}\n";
    echo "   Count: {$result3['total']} recommendations\n";
    echo "   Time: " . round($time3, 2) . " ms\n";
} else {
    echo "   ❌ Failed: {$result3['message']}\n";
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  📊 BENCHMARK RESULTS                                          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Calculate statistics
$avgCacheTime = ($time2 + $time3) / 2;
$speedup = $time1 / $avgCacheTime;

echo "┌─────────────────────────────────────────────────────────────┐\n";
printf("│ %-30s │ %15s │ %10s │\n", "Call Type", "Time (ms)", "Source");
echo "├─────────────────────────────────────────────────────────────┤\n";
printf("│ %-30s │ %15s │ %10s │\n", 
    "Call 1: API + Cache", 
    round($time1, 2), 
    $result1['source']
);
printf("│ %-30s │ %15s │ %10s │\n", 
    "Call 2: Cache Hit", 
    round($time2, 2), 
    $result2['source']
);
printf("│ %-30s │ %15s │ %10s │\n", 
    "Call 3: Cache Hit", 
    round($time3, 2), 
    $result3['source']
);
echo "└─────────────────────────────────────────────────────────────┘\n";
echo "\n";

echo "┌─────────────────────────────────────────────────────────────┐\n";
echo "│ 📈 PERFORMANCE METRICS                                      │\n";
echo "├─────────────────────────────────────────────────────────────┤\n";
printf("│ API Call Time:          %10.2f ms                      │\n", $time1);
printf("│ Average Cache Hit Time: %10.2f ms                      │\n", $avgCacheTime);
printf("│ Time Saved per Request: %10.2f ms                      │\n", $time1 - $avgCacheTime);
printf("│ Speedup Factor:         %10.1f x                       │\n", $speedup);
echo "└─────────────────────────────────────────────────────────────┘\n";
echo "\n";

// Step 5: Cache Statistics
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  📊 CACHE STATISTICS                                           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

$stats = MLRecommendationCache::getCacheStats();

echo "┌─────────────────────────────────────────────────────────────┐\n";
printf("│ %-20s │ %8s │ %15s │ %10s │\n", "Survey Type", "Count", "Cached At", "Status");
echo "├─────────────────────────────────────────────────────────────┤\n";

$rtStatus = $stats['rumah_tangga']['is_stale'] ? '🔴 Stale' : '🟢 Fresh';
$ptStatus = $stats['perusahaan']['is_stale'] ? '🔴 Stale' : '🟢 Fresh';

printf("│ %-20s │ %8d │ %15s │ %10s │\n", 
    'Rumah Tangga',
    $stats['rumah_tangga']['count'],
    substr($stats['rumah_tangga']['cached_at'] ?? 'Never', 0, 10),
    $rtStatus
);

printf("│ %-20s │ %8d │ %15s │ %10s │\n", 
    'Perusahaan',
    $stats['perusahaan']['count'],
    substr($stats['perusahaan']['cached_at'] ?? 'Never', 0, 10),
    $ptStatus
);

echo "├─────────────────────────────────────────────────────────────┤\n";
printf("│ %-20s │ %8d │ %15s │ %10s │\n", 
    'TOTAL',
    $stats['total_cached'],
    '-',
    '-'
);
echo "└─────────────────────────────────────────────────────────────┘\n";
echo "\n";

// Step 6: Recommendations
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  💡 RECOMMENDATIONS                                            ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

if ($speedup >= 10) {
    echo "✅ Excellent! Cache is providing significant performance boost.\n";
} elseif ($speedup >= 5) {
    echo "✅ Good! Cache is working well.\n";
} elseif ($speedup >= 2) {
    echo "⚠️  Moderate speedup. Consider optimizing database indexes.\n";
} else {
    echo "❌ Warning! Cache not providing expected performance gain.\n";
    echo "   Check:\n";
    echo "   - Database indexes\n";
    echo "   - MySQL query cache settings\n";
    echo "   - Network latency to ML API\n";
}

echo "\n";

if ($result2['source'] !== 'mysql_cache' || $result3['source'] !== 'mysql_cache') {
    echo "⚠️  Warning: Some calls did not hit cache!\n";
    echo "   Expected source: 'mysql_cache'\n";
    echo "   Call 2 source: {$result2['source']}\n";
    echo "   Call 3 source: {$result3['source']}\n";
    echo "\n";
}

echo "Next Steps:\n";
echo "  1. Run 'php artisan ml:refresh-cache' to refresh cache manually\n";
echo "  2. Monitor cache freshness daily\n";
echo "  3. Check 'storage/logs/laravel.log' for cache hit/miss logs\n";
echo "  4. Test with production load\n";
echo "\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  ✅ BENCHMARK COMPLETE                                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";
