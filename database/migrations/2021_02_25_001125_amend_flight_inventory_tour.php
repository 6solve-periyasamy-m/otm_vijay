<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AmendFlightInventoryTour extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('flight_inventory_tour', function(Blueprint $table) {
        //     $table->string('flight_type', 20)->nullable();
        //     $table->renameColumn('tour_component_type', 'tour_component_type_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('flight_inventory_tour', function(Blueprint $table) {
        //     $table->dropColumn(['flight_type']);
        //     $table->renameColumn('tour_component_type_id', 'tour_component_type');
        // });
    }
}
