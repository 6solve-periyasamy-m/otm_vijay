<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_address', 255);
            $table->string('password')->nullable();
            $table->string('login_token', 255)->nullable();
            $table->string('gender', 6);
            $table->string('title', 20)->nullable();
            $table->string('first_name', 160);
            $table->string('middle_names', 160)->nullable();
            $table->string('last_name', 160);
            $table->date('date_of_birth');
            $table->string('mobile_number', 25);
            $table->string('other_phone_number', 25)->nullable();
            $table->integer('home_address_id');
            $table->integer('billing_address_id');
            $table->string('emergency_contact_name', 160)->nullable();
            $table->string('emergency_contact_relationship', 80)->nullable();
            $table->string('emergency_contact_telephone', 120)->nullable();
            $table->string('passport_first_name', 160)->nullable();
            $table->string('passport_middle_name', 160)->nullable();
            $table->string('passport_last_name', 160)->nullable();
            $table->string('passport_number', 60)->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_country_of_issue', 60)->nullable();
            $table->string('loyalty_number', 160)->nullable();
            $table->integer('t_shirt_size_id')->nullable();
            $table->integer('hat_size_id')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
