<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteCascadeInHolidayAndSicknesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
        });
        
        
        Schema::table('sicknesses', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            // These are the default states of cascading.
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('restrict');
        });
        
        Schema::table('sicknesses', function (Blueprint $table) {
            // These are the default states of cascading.
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('restrict');
        });
    }
}
