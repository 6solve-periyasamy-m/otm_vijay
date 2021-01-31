<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function(Blueprint $table) {
            // $table->integer('transport_id')->index();
            // $table->integer('flight_id')->index();
            // $table->integer('accommodation_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function(Blueprint $table) {
            // $table->dropColumn('transport_id');
            // $table->dropColumn('flight_id');
            // $table->dropColumn('accommodation_id');
        });
    }
}
