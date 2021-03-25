<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTravelClassToTransportInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_inventories', function (Blueprint $table) {
            // tests indicate this must be nullable (i.e. no class assigned to the transport)
            // TODO: make this madatory (not-nullable)?
            $table->integer('travel_class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->dropColumn('travel_class_id');
        });
    }
}
