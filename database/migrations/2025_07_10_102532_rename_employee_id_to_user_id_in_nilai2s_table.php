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
        Schema::table('nilai2s', function (Blueprint $table) {
            // Drop the old foreign key
            $table->dropForeign(['employee_id']);
            // Rename the column
            $table->renameColumn('employee_id', 'user_id');
        });

        Schema::table('nilai2s', function (Blueprint $table) {
            // Add the new foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('nilai2s', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'employee_id');
        });

        Schema::table('nilai2s', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
};
