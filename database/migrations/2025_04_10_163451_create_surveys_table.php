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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50);
            $table->string('code', 50); // assuming survey code is unique
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('rate');
            $table->string('file', 255)->nullable();

            $table->boolean('is_scored')->default(false); // should have default value
            $table->boolean('is_synced')->default(false);

            $table->enum('status', ['not started', 'in progress', 'done'])->default('not started');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
