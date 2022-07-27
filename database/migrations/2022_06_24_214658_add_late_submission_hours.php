<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLateSubmissionHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours', function (Blueprint $table) {
            $table->float('late_submission_hours')->after('late_submission_reason')->default(0.00);
            $table->unsignedTinyInteger('no_of_words_in_comments')->after('comments')->default(0);
            $table->unsignedTinyInteger('no_of_images')->after('images')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hours', function (Blueprint $table) {
            $table->dropColumn(['late_submission_hours', 'no_of_words_in_comments', 'no_of_images']);
        });
    }
}
