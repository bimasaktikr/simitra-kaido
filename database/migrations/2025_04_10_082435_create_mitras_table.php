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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('sobat_id');
            $table->string('name', 200);
            $table->foreignId('user_id')->constrained();
            $table->string('email', 200)->unique();
            $table->string('pendidikan', 50);
            $table->string('jenis_kelamin', 50);
            $table->date('tanggal_lahir');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
