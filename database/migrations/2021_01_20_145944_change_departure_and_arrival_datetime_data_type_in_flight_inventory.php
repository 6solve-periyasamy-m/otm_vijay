<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepartureAndArrivalDatetimeDataTypeInFlightInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->dropColumn("departure_time");
            $table->dropColumn("arrival_time");
            $table->dateTime("departure_date_time");
            $table->dateTime("arrival_date_time");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->dropColumn("departure_date_time");
            $table->dropColumn("arrival_date_time");
            $table->time("departure_time");
            $table->time("arrival_time");
        });
    }
}
