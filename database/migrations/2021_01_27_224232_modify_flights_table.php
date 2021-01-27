<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flights', function(Blueprint $table) {
            $table->time('departure_time')->default('00:00');
            $table->time('arrival_time')->default('00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flights', function(Blueprint $table) {
            $table->dropColumn('departure_time');
            $table->dropColumn('arrival_time');
        });
    }
}
