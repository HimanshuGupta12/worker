<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyNameToUserBillingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_billing_details', function (Blueprint $table) {
            $table->integer('phone_country')->nullable()->default(null);
            $table->string('company_name')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_billing_details', function (Blueprint $table) {
            $table->dropColumn('phone_country');
            $table->dropColumn('company_name');
        });
    }
}
