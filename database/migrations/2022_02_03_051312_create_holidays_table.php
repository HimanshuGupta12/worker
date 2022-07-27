<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('company_id')->constrained();
            $table->string('request_type')->nullable();
            $table->string('leave_duration')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(3)->comment('1 => Approved, 2 => Not Approved, 3 => Pending, 4 => Delete Request');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('holidays');
    }
}
