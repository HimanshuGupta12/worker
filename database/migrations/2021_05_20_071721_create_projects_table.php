<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('name');
            $table->string('address', 1023)->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->integer('break_time')->nullable();
            $table->foreignId('manager_id')->constrained('workers');
            $table->integer('allow_comments')->default(0)->comment('0 => No, 1 => Yes');
            $table->integer('allow_photos')->default(0)->comment('0 => No, 1 => Yes');
            $table->integer('is_explainer_text')->default(0)->comment('0 => No, 1 => Yes');
            $table->text('explainer_text', 1023)->nullable();
            // $table->unsignedInteger('client_id');
            // $table->foreign('client_id')->references('id')->on('clients');
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
        Schema::dropIfExists('projects');
    }
}
