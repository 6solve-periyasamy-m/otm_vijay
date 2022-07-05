<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOldBookingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('booking_travellers');
        Schema::drop('booking_accommodations');
        Schema::drop('booking_activities');
        Schema::drop('booking_flights');
        Schema::drop('booking_transports');
        Schema::drop('booking_merchandises');
        Schema::drop('bookings');
        Schema::drop('accommodation_groups');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('tour_id')->constrained();
            $table->string('token', 64)->nullable();
            $table->string('status',64)->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('accommodation_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
        });
        Schema::create('booking_travellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->timestamps();
        });
        Schema::create('booking_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('accommodation_inventory_tour_id')->constrained();
            $table->foreignId('room_type_id')->constrained();
            $table->integer('group_id')->nullable();
            $table->timestamps();
        });
        Schema::create('booking_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('activity_inventory_tour_id')->constrained();
            $table->timestamps();
        });
        Schema::create('booking_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('flight_inventory_tour_id')->constrained();
            $table->string('flight_type');
            $table->timestamps();
        });
        Schema::create('booking_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('transport_inventory_tour_id')->constrained();
            $table->timestamps();
        });
        Schema::create('booking_merchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('merchandise_id')->constrained();
            $table->timestamps();
        });
    }
}
