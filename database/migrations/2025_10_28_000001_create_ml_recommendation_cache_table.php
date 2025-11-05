<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ml_recommendation_cache', function (Blueprint $table) {
            $table->id();
            
            // Core fields - sesuai response API
            $table->string('survey_type', 50)->index(); // 'Rumah Tangga' or 'Perusahaan'
            $table->unsignedBigInteger('mitra_id')->index();
            $table->string('mitra_name');
            
            // ML Scores - exact 10 fields dari API
            $table->decimal('survey_score', 10, 4)->default(0)->comment('Average rating from surveys');
            $table->integer('jumlah_survey')->default(0)->comment('Total survey count');
            $table->decimal('exp_norm', 10, 8)->default(0)->comment('Experience normalized');
            $table->decimal('weighted_score', 10, 8)->default(0)->comment('Weighted score');
            $table->decimal('optimized_score', 10, 8)->default(0)->comment('Rating Mitra from PSO');
            $table->decimal('final_rank_score', 10, 8)->default(0)->comment('ML Score (combined)');
            
            // Timestamps
            $table->timestamp('created_at')->nullable()->comment('From ML API');
            $table->timestamp('cached_at')->useCurrent()->comment('When data was cached to MySQL');
            $table->timestamp('updated_at')->nullable();
            
            // Composite index for fast lookups
            $table->index(['survey_type', 'final_rank_score']);
            
            // Unique constraint per survey type and mitra
            $table->unique(['survey_type', 'mitra_id'], 'unique_survey_mitra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_recommendation_cache');
    }
};
