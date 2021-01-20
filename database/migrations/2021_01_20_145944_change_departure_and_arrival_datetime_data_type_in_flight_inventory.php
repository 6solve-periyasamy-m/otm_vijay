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
            $table->dateTime("arrival_date_time")->change();
            $table->dateTime("arrival_date_time")->change();
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
            $table->time("arrival_time")->change();
            $table->time("arrival_time")->change();
        });
    }
}
