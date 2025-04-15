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
        Schema::create('mitra_teladans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mitra_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('team_id')
                ->constrained()
                ->onDelete('cascade');

            $table->year('year');

            $table->unsignedTinyInteger('quarter');

            $table->decimal('avg_rating_1', 4, 2)->nullable(); // allows up to 99.99
            $table->decimal('avg_rating_2', 4, 2)->nullable();

            $table->unsignedSmallInteger('surveys_count')->default(0);

            $table->boolean('status_phase_2')->nullable();

            $table->timestamps();

            $table->index(['year', 'quarter']);
            $table->index('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_teladans');
    }
};
