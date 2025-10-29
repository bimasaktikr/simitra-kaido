<?php

namespace App\Console\Commands;

use App\Services\MLRecommendationService;
use App\Models\MLRecommendationCache;
use Illuminate\Console\Command;

class RefreshMLCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:refresh-cache 
                            {type? : Survey type (Rumah Tangga/Perusahaan) or "all"} 
                            {--force : Force refresh even if cache is fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh ML recommendation cache from API (fetches ALL recommendations)';

    protected MLRecommendationService $mlService;

    public function __construct(MLRecommendationService $mlService)
    {
        parent::__construct();
        $this->mlService = $mlService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type') ?? 'all';
        $force = $this->option('force');

        $this->info("🔄 Starting ML cache refresh...");
        $this->info("Type: {$type}");
        $this->info("Mode: Fetch ALL recommendations (no limit)");
        $this->info("Force: " . ($force ? 'Yes' : 'No'));
        $this->newLine();

        // Determine which types to refresh
        $types = match(strtolower($type)) {
            'rumah tangga', 'rumahtangga', 'rt' => ['Rumah Tangga'],
            'perusahaan', 'pt' => ['Perusahaan'],
            default => ['Rumah Tangga', 'Perusahaan'] // 'all' or invalid = both
        };

        $success = true;
        foreach ($types as $surveyType) {
            $this->info("📊 Processing: {$surveyType}");
            
            // Check if refresh needed (unless forced)
            if (!$force && !MLRecommendationCache::isCacheStale($surveyType)) {
                $this->warn("⏭️  Skipping {$surveyType} - cache is still fresh");
                $this->info("   Use --force to refresh anyway");
                continue;
            }

            // Refresh cache (fetch ALL recommendations, no limit)
            $this->info("🔄 Fetching ALL recommendations from ML API...");
            $result = $this->mlService->refreshCache($surveyType, 9999); // Large number to get all

            if ($result['success']) {
                $this->info("✅ Successfully refreshed cache for {$surveyType}");
                $this->info("   Cached: {$result['total']} recommendations");
                $this->info("   Source: {$result['source']}");
            } else {
                $this->error("❌ Failed to refresh cache for {$surveyType}");
                $this->error("   Error: {$result['message']}");
                $success = false;
            }

            $this->newLine();
        }

        // Show cache statistics
        $this->showCacheStats();

        return $success ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Display cache statistics
     */
    protected function showCacheStats(): void
    {
        $this->info("📊 Cache Statistics:");
        $this->newLine();

        // Get stats from both tables using dynamic model
        $rumahTanggaCount = MLRecommendationCache::forSurveyType('Rumah Tangga')->count();
        $perusahaanCount = MLRecommendationCache::forSurveyType('Perusahaan')->count();
        
        $rumahTanggaLatest = MLRecommendationCache::forSurveyType('Rumah Tangga')->latest('cached_at')->first();
        $perusahaanLatest = MLRecommendationCache::forSurveyType('Perusahaan')->latest('cached_at')->first();

        $this->table(
            ['Survey Type', 'Count', 'Cached At', 'Status'],
            [
                [
                    'Rumah Tangga',
                    $rumahTanggaCount,
                    $rumahTanggaLatest?->cached_at ?? 'Never',
                    MLRecommendationCache::isCacheStale('Rumah Tangga') ? '🔴 Stale' : '🟢 Fresh'
                ],
                [
                    'Perusahaan',
                    $perusahaanCount,
                    $perusahaanLatest?->cached_at ?? 'Never',
                    MLRecommendationCache::isCacheStale('Perusahaan') ? '🔴 Stale' : '🟢 Fresh'
                ],
                [
                    'TOTAL',
                    $rumahTanggaCount + $perusahaanCount,
                    '-',
                    '-'
                ]
            ]
        );

        $this->newLine();
        $this->info("💡 Tips:");
        $this->line("   - Cache is considered stale after 24 hours");
        $this->line("   - Use --force to refresh fresh cache");
        $this->line("   - Use type argument to refresh specific type only");
        $this->line("   - Command always fetches ALL recommendations (no limit)");
        $this->line("   - Example: php artisan ml:refresh-cache \"Rumah Tangga\" --force");
    }
}
