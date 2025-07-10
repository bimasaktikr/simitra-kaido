<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->unsignedBigInteger('master_survey_id')->nullable()->after('id');
            $table->unsignedTinyInteger('triwulan')->nullable()->after('master_survey_id');
        });
    }
    public function down()
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['master_survey_id', 'triwulan']);
        });
    }
};
