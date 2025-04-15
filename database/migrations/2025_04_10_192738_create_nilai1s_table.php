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
        Schema::create('nilai1s', function (Blueprint $table) {
            $table->foreignId('transaction_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedTinyInteger('aspek1');
            $table->unsignedTinyInteger('aspek2');
            $table->unsignedTinyInteger('aspek3');

            $table->decimal('rerata', 5, 2); // allows 999.99 (if ever needed)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai1s');
    }
};
