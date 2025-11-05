<?php

namespace App\Console\Commands;

use App\Models\MasterSurvey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncMasterSurveyTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:sync-master-survey-types
                            {--force : Force sync even if no mismatches detected}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master survey types from PostgreSQL ML backend to MySQL Laravel database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔄 Syncing Master Survey Types from ML Backend...");
        $this->newLine();

        try {
            // Method 1: Try PostgreSQL PDO connection first
            $masterSurveys = $this->fetchFromPostgres();
            
            // If PDO not available, fallback to API
            if (empty($masterSurveys)) {
                $this->line("⚠️ PostgreSQL PDO not available, using FastAPI endpoint...");
                $masterSurveys = $this->fetchFromAPI();
            }

            if (empty($masterSurveys)) {
                $this->error("❌ No master surveys found!");
                return 1;
            }

            $this->line("📊 Found " . count($masterSurveys) . " master surveys from ML backend");
            $this->newLine();

            // Compare and update MySQL
            $stats = [
                'total' => 0,
                'matched' => 0,
                'updated' => 0,
                'missing_in_mysql' => 0,
                'errors' => 0,
            ];

            $mismatches = [];

            $this->line("🔍 Checking for mismatches...");
            $bar = $this->output->createProgressBar(count($masterSurveys));
            $bar->start();

            foreach ($masterSurveys as $pgSurvey) {
                $stats['total']++;
                
                $mysqlSurvey = MasterSurvey::find($pgSurvey['id']);
                
                if (!$mysqlSurvey) {
                    $stats['missing_in_mysql']++;
                    $mismatches[] = [
                        'id' => $pgSurvey['id'],
                        'code' => $pgSurvey['code'],
                        'pg_type' => $pgSurvey['type'],
                        'mysql_type' => 'NOT FOUND',
                        'status' => 'missing'
                    ];
                    $bar->advance();
                    continue;
                }

                // Check if types match
                if ($mysqlSurvey->type !== $pgSurvey['type']) {
                    $mismatches[] = [
                        'id' => $pgSurvey['id'],
                        'code' => $pgSurvey['code'],
                        'pg_type' => $pgSurvey['type'],
                        'mysql_type' => $mysqlSurvey->type,
                        'status' => 'mismatch'
                    ];

                    // Update MySQL
                    try {
                        $mysqlSurvey->type = $pgSurvey['type'];
                        $mysqlSurvey->save();
                        $stats['updated']++;
                    } catch (\Exception $e) {
                        $stats['errors']++;
                        Log::error("Failed to update master_survey_id {$pgSurvey['id']}", [
                            'error' => $e->getMessage()
                        ]);
                    }
                } else {
                    $stats['matched']++;
                }
                
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            // Display results
            if (!empty($mismatches)) {
                $this->warn("⚠️  Found " . count($mismatches) . " mismatch(es):");
                $this->newLine();

                $tableData = [];
                foreach ($mismatches as $mismatch) {
                    $tableData[] = [
                        'ID' => $mismatch['id'],
                        'Code' => $mismatch['code'],
                        'PostgreSQL' => $mismatch['pg_type'],
                        'MySQL (Before)' => $mismatch['mysql_type'],
                        'Status' => $mismatch['status'] === 'mismatch' ? '✅ Fixed' : '❌ Missing'
                    ];
                }

                $this->table(
                    ['ID', 'Code', 'PostgreSQL', 'MySQL (Before)', 'Status'],
                    $tableData
                );
                $this->newLine();
            }

            // Summary
            $this->info("📊 Sync Summary:");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("Total Master Surveys: {$stats['total']}");
            $this->line("Already Matched: {$stats['matched']}");
            $this->line("Updated: {$stats['updated']} ✅");
            
            if ($stats['missing_in_mysql'] > 0) {
                $this->line("Missing in MySQL: {$stats['missing_in_mysql']} ⚠️");
            }
            
            if ($stats['errors'] > 0) {
                $this->line("Errors: {$stats['errors']} ❌");
            }
            
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->newLine();

            if ($stats['updated'] > 0) {
                $this->info("✅ Successfully synced {$stats['updated']} master survey type(s)!");
            } else {
                $this->info("✅ All master survey types are already in sync!");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Sync Error:");
            $this->line($e->getMessage());
            Log::error("Master survey type sync failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Fetch master surveys from PostgreSQL using PDO
     */
    protected function fetchFromPostgres(): array
    {
        try {
            // Check if pgsql extension is available
            if (!extension_loaded('pdo_pgsql')) {
                return [];
            }

            $pgHost = env('ML_POSTGRES_HOST', 'localhost');
            $pgPort = env('ML_POSTGRES_PORT', '5432');
            $pgDatabase = env('ML_POSTGRES_DATABASE', 'mitra_kaido');
            $pgUsername = env('ML_POSTGRES_USERNAME', 'postgres');
            $pgPassword = env('ML_POSTGRES_PASSWORD', 'postgres');

            // Create PDO connection to PostgreSQL
            $dsn = "pgsql:host={$pgHost};port={$pgPort};dbname={$pgDatabase}";
            $pdo = new \PDO($dsn, $pgUsername, $pgPassword, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);

            // Fetch master surveys from PostgreSQL
            $stmt = $pdo->query("
                SELECT id, name, code, type 
                FROM master_surveys_enriched 
                ORDER BY id
            ");
            
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            Log::warning("PostgreSQL PDO connection failed", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Fetch master surveys from ML API endpoint (fallback)
     */
    protected function fetchFromAPI(): array
    {
        try {
            $apiUrl = env('ML_API_BASE_URL', 'http://localhost:8001');
            $response = \Http::timeout(10)->get("{$apiUrl}/api/master-surveys");

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }

            Log::warning("Failed to fetch from API", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [];
        } catch (\Exception $e) {
            Log::error("API fetch failed", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}
