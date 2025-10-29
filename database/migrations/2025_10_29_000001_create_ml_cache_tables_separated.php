<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Membuat 2 table terpisah untuk menghindari konflik ranking antar survey type
     */
    public function up(): void
    {
        // 1. Backup data dari table lama
        $oldData = DB::table('ml_recommendation_cache')->get();
        
        // 2. Drop table lama
        Schema::dropIfExists('ml_recommendation_cache');
        
        // 3. Buat table untuk Rumah Tangga
        Schema::create('ml_cache_rumah_tangga', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_id')->index();
            $table->string('mitra_name');
            
            // ML Scores - 10 fields sesuai API
            $table->decimal('survey_score', 10, 4)->default(0);
            $table->integer('jumlah_survey')->default(0);
            $table->decimal('exp_norm', 10, 8)->default(0);
            $table->decimal('weighted_score', 10, 8)->default(0);
            $table->decimal('optimized_score', 10, 8)->default(0);
            $table->decimal('final_rank_score', 10, 8)->default(0)->comment('ML ranking score');
            
            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('cached_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            
            // Indexes
            $table->unique('mitra_id');
            $table->index('final_rank_score');
        });
        
        // 4. Buat table untuk Perusahaan
        Schema::create('ml_cache_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_id')->index();
            $table->string('mitra_name');
            
            // ML Scores - 10 fields sesuai API
            $table->decimal('survey_score', 10, 4)->default(0);
            $table->integer('jumlah_survey')->default(0);
            $table->decimal('exp_norm', 10, 8)->default(0);
            $table->decimal('weighted_score', 10, 8)->default(0);
            $table->decimal('optimized_score', 10, 8)->default(0);
            $table->decimal('final_rank_score', 10, 8)->default(0)->comment('ML ranking score');
            
            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('cached_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            
            // Indexes
            $table->unique('mitra_id');
            $table->index('final_rank_score');
        });
        
        // 5. Restore data ke table yang sesuai
        foreach ($oldData as $record) {
            $data = [
                'mitra_id' => $record->mitra_id,
                'mitra_name' => $record->mitra_name,
                'survey_score' => $record->survey_score,
                'jumlah_survey' => $record->jumlah_survey,
                'exp_norm' => $record->exp_norm,
                'weighted_score' => $record->weighted_score,
                'optimized_score' => $record->optimized_score,
                'final_rank_score' => $record->final_rank_score,
                'created_at' => $record->created_at,
                'cached_at' => $record->cached_at,
                'updated_at' => $record->updated_at,
            ];
            
            if ($record->survey_type === 'Rumah Tangga') {
                DB::table('ml_cache_rumah_tangga')->insert($data);
            } elseif ($record->survey_type === 'Perusahaan') {
                DB::table('ml_cache_perusahaan')->insert($data);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_cache_rumah_tangga');
        Schema::dropIfExists('ml_cache_perusahaan');
        
        // Restore old table structure
        Schema::create('ml_recommendation_cache', function (Blueprint $table) {
            $table->id();
            $table->string('survey_type', 50)->index();
            $table->unsignedBigInteger('mitra_id')->index();
            $table->string('mitra_name');
            $table->decimal('survey_score', 10, 4)->default(0);
            $table->integer('jumlah_survey')->default(0);
            $table->decimal('exp_norm', 10, 8)->default(0);
            $table->decimal('weighted_score', 10, 8)->default(0);
            $table->decimal('optimized_score', 10, 8)->default(0);
            $table->decimal('final_rank_score', 10, 8)->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('cached_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['survey_type', 'mitra_id'], 'unique_survey_mitra');
            $table->index(['survey_type', 'final_rank_score']);
        });
    }
};
