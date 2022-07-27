<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->unsignedBigInteger('company_tool_id');
            $table->string('tool_code', 50)->nullable();
            $table->string('name');
            $table->string('images', 1024)->nullable();
            $table->string('model')->nullable();
            $table->decimal('price')->nullable();
            $table->date('purchased_at')->nullable();
            $table->foreignId('tool_category_id')->nullable()->constrained();
            $table->unsignedBigInteger('status_id')->default(1);
            $table->timestamp('status_changed_at')->nullable()->comment('for status "in service", notify every 3 weeks');
            $table->string('status_description')->nullable()->comment('for statuses like lost/broken');
            $table->string('status_photo')->nullable();
            $table->unsignedBigInteger('possessor_id')->nullable()->index();
            $table->string('possessor_type')->nullable();
            $table->timestamp('inventoried_at')->nullable();
            $table->date('next_inventorization_at')->nullable();
            $table->boolean('notified')->default(false)->comment('got sms that needs to inventory');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('status_id')->references('id')->on('tool_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tools');
    }
}
