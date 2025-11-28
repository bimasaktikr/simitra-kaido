<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_qrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->unique()->constrained('transactions')->cascadeOnDelete();
            $table->uuid('uuid')->unique();
            $table->string('qr_path')->nullable();       // storage path qr
            $table->string('id_card_path')->nullable();  // storage path pdf 1 kartu
            $table->string('qr_status')->default('pending');      // pending|processing|done|failed
            $table->string('id_card_status')->default('pending'); // pending|processing|done|failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_qrs');
    }
};
