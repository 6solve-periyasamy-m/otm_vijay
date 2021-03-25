<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // $table->date('date_of_birth')->after('last_name')->nullable();
            // $table->string('first_name', 160)->nullable(false)->change();
            // $table->string('last_name', 160)->nullable(false)->change();
            // $table->string('emergency_contact_name', 160)->nullable()->change();
            // $table->string('emergency_contact_relationship', 80)->nullable()->change();
            // $table->string('emergency_contact_telephone', 120)->nullable()->change();
            // $table->string('mobile_number', 120)->change();
            // $table->string('other_phone_number', 120)->change();
            // $table->string('gender', 80)->change();
            // $table->string('passport_first_name', 160)->change();
            // $table->string('passport_middle_name', 160)->change();
            // $table->string('passport_last_name', 160)->change();
            // $table->string('passport_country_of_issue', 120)->change();
            // $table->string('loyalty_number', 160)->nullable()->change();
            // // address fields
            // $table->string('address_line_1', 160)->nullable()->change();
            // $table->string('address_line_2', 120)->nullable()->change();
            // $table->string('address_line_3', 120)->nullable()->change();
            // $table->string('town', 120)->nullable()->change();
            // $table->string('country', 120)->nullable()->change();
            // $table->string('postcode', 24)->nullable()->change();
            // // add billing fields
            // $table->string('billing_line_1', 160)->nullable();
            // $table->string('billing_line_2', 120)->nullable();
            // $table->string('billing_line_3', 120)->nullable();
            // $table->string('billing_town', 120)->nullable();
            // $table->string('billing_country', 120)->nullable();
            // $table->string('billing_postcode', 24)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //$table->dropColumn(['date_of_birth','billing_line_1', 'billing_line_2', 'billing_line_3', 'billing_town', 'billing_country', 'billing_postcode']);
        });
    }
}
