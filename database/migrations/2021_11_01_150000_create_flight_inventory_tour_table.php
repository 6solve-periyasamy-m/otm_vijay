<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightInventoryTourTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_inventory_tour', function (Blueprint $table) {
            $table->id();
            $table->integer('tour_id');
            $table->integer('flight_inventory_id');
            $table->enum('tour_component_type', ['Included', 'Add-on', 'Upgrade'])->default('Included');
            $table->float('tour_sales_price', 10, 0)->nullable();
            $table->string('flight_type', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flight_inventory_tour');
    }
}
