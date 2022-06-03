<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewBookingTravellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_travellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->string('title', 20)->nullable();
            $table->string('first_name', 160)->nullable();
            $table->string('middle_names', 160)->nullable();
            $table->string('last_name', 160)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('mobile_number', 25)->nullable();
            $table->string('email_address', 255)->nullable();
            $table->foreignId('home_address_id')->nullable()->constrained('addresses')->onDelete('cascade');
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('cascade');
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
        Schema::dropIfExists('booking_travellers');
    }
}
