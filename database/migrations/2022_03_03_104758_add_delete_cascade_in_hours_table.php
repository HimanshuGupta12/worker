<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteCascadeInHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
        Schema::table('hours', function (Blueprint $table) {
            // These are the default states of cascading.
            $table->dropForeign(['project_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('restrict');
            $table->dropForeign(['worker_id']);
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('restrict');
        });
    }
}
