<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartureAndArrivalDatetimeToFlightInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->renameColumn('departure_time', 'departure_date_time');
            $table->renameColumn('arrival_time', 'arrival_date_time');
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
            $table->renameColumn('departure_date_time', 'departure_time');
            $table->renameColumn('arrival_date_time', 'arrival_time');
        });
    }
}
