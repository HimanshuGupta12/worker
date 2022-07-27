<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSicknessDaysInSicknessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Drop extra columns which are never used*/
//        Schema::table('sicknesses', function (Blueprint $table) {
//            $table->dropColumn(['report', 'report_type', 'request_type', 'period', 'time_from', 'time_to', 'report', 'report']);
//        });
        
        
        Schema::table('sicknesses', function (Blueprint $table) {
            $table->string('leave_duration')->after('date_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sicknesses', function (Blueprint $table) {
            $table->dropColumn(['leave_duration']);
        });
    }
}
