<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_billing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('email')->nullable()->default(null);
            $table->string('address_line', 1000);
            $table->string('city');
            $table->string('state')->nullable()->default(null);
            $table->string('country', 2);
            $table->string('postal_code', 10);
            $table->integer('phone_number')->nullable()->default(null);
            $table->string('vat_type')->nullable()->default(null);
            $table->string('vat_number')->nullable()->default(null);
            $table->string('vat_number_stripe_id')->nullable()->default(null);
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
        Schema::dropIfExists('user_billing_details');
    }
}
