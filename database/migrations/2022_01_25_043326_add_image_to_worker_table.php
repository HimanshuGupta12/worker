<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('workers', 'add_tool')) {
            Schema::table('workers', function (Blueprint $table) {
                $table->boolean('add_tool')->default(false);
            });
        }
        
        Schema::table('workers', function (Blueprint $table) {
            //$table->boolean('add_tool')->default(false);
            $table->boolean('see_hours')->default(false);
            $table->boolean('edit_hours')->default(false);
            $table->string('images')->nullable();
            $table->string('worker_position')->nullable();
            $table->string('language_settings')->nullable();
            $table->boolean('economical_data')->default(false);
            $table->string('worker_cost')->nullable();
            $table->boolean('status')->default(true)->comment('0 =>Inactive, 1=>Active');
            $table->boolean('loose_access')->default(false);
            $table->boolean('hide_worker')->default(false);

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
            $table->dropColumn(['add_tool', 'see_hours','edit_hours', 'images','worker_position', 'language_settings','economical_data', 'worker_cost','status','loose_access','hide_worker']);

        });
    }
}
