<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai2s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_teladan_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->integer('aspek1');
            $table->integer('aspek2');
            $table->integer('aspek3');
            $table->integer('aspek4');
            $table->integer('aspek5');
            $table->integer('aspek6');
            $table->integer('aspek7');
            $table->integer('aspek8');
            $table->integer('aspek9');
            $table->integer('aspek10');
            $table->float('rerata', 3, 2);
            $table->boolean('is_final')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai2');
    }
};
