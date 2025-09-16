<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maximal_payments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Monthly Cap');
            // store rupiah as integer (no decimals)
            $table->unsignedBigInteger('value')->default(0);
            // singleton flag: we’ll keep only one row with singleton = true
            $table->boolean('singleton')->default(true);
            $table->unique('singleton', 'maxpay_singleton_unique');
            $table->timestamps();
        });

        // Seed the single row
        DB::table('maximal_payments')->insert([
            'name'       => 'SBML',
            'value'      => 0,          // set your default cap here (e.g. 2000000)
            'singleton'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('maximal_payments');
    }
};
