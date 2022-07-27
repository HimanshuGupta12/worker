<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('worker_id')->constrained();
            $table->date('work_day');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('lunch_break');
            $table->integer('break_time')->default(0)->comment('In Minutes');
            $table->text('comments')->nullable();
            $table->string('images', 1024)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hours');
    }
}
