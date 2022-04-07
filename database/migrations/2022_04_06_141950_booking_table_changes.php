<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookingTableChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function(Blueprint $table) {
            $table->string('status',64)->nullable();
        });
        Schema::table('booking_accommodations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('accommodation_inventory_tour_id');
            $table->foreignId('room_type_id')->constrained();
            $table->integer('group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function(Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('booking_accommodations', function (Blueprint $table) {
            $table->foreignId('accommodation_inventory_tour_id')->constrained();
            $table->dropConstrainedForeignId('room_type_id');
            $table->dropColumn('group_id');
        });
    }
}
