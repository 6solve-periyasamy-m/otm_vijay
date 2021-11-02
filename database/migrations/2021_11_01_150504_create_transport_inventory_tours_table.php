<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportInventoryToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_inventory_tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id');
            $table->foreignId('transport_inventory_id');
            $table->enum('tour_component_type', ['Included', 'Add-on', 'Upgrade'])->default('Included');
            $table->float('tour_sales_price', 10, 0)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->foreign('transport_inventory_id')->references('id')->on('transport_inventories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_inventory_tours');
    }
}
