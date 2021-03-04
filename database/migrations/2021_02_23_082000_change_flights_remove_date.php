<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFlightsRemoveDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flights', function(Blueprint $table) {
            $table->dropColumn(['departure_date', 'arrival_date']);
            $table->date('available_after')->nullable();
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
            $table->dropColumn('available_after');
            $table->date('departure_date')->nullable();
            $table->date('arrival_date')->nullable();
        });
    }
}
