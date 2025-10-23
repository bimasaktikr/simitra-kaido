<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // UUID unik transaksi (kombinasi mitra×survey)
            $table->uuid('uuid')->nullable()->unique()->after('id');

            // path file QR (optional, boleh null)
            $table->string('qr_path')->nullable()->after('uuid');

            // jaga integritas: satu mitra hanya sekali di satu survey
            $table->unique(['mitra_id', 'survey_id']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique(['mitra_id', 'survey_id']);
            $table->dropColumn(['uuid', 'qr_path']);
        });
    }
};