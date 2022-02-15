<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('room_type_id')->constrained();
            $table->integer('group_id')->nullable();
            //$table->integer('group_id')->constrained();
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
        Schema::dropIfExists('booking_accommodations');
    }
}
