<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('phone_country')->nullable()->default(null);
            $table->integer('phone_number')->nullable()->default(null);
            $table->boolean('change_tool_status')->default(false);
            $table->boolean('scan_to_storage')->default(false);
            $table->boolean('inventory_storage')->default(false);
            $table->boolean('see_company_tools')->default(false);
            $table->boolean('add_tool')->default(false);
            $table->string('login', 20)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers');
    }
}
