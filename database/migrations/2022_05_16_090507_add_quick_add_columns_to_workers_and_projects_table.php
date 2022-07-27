<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuickAddColumnsToWorkersAndProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->boolean('quick_add')->default(false);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('quick_add')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn(['quick_add']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['quick_add']);
        });
    }
}
