<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSicknessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sicknesses')) {
            Schema::create('sicknesses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('worker_id')->constrained();
                $table->foreignId('company_id')->constrained();
                $table->text('description')->nullable();
                $table->date('date_from')->nullable();
                $table->date('date_to')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sicknesses');
    }
}
