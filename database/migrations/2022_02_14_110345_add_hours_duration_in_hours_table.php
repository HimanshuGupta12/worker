<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoursDurationInHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours', function (Blueprint $table) {
            $table->foreignId('company_id')->after('worker_id')->nullable()->constrained('companies')->default(NULL);
            $table->float('working_hours', 8, 2)->after('break_time')->nullable()->default(0.00)->comment('The calculated hours in float value.');
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
            $table->dropForeign(['company_id']);
            $table->dropColumn(['working_hours', 'company_id']);
        });
    }
}
