<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationInventoryToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_inventory_tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id');
            $table->foreignId('accommodation_inventory_id');
            $table->float('tour_sales_price', 10, 0)->nullable();
            $table->enum('tour_component_type', ['Included', 'Add-on', 'Upgrade'])->default('Included');
            $table->string('booking_policy', 12)->default('overbook');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->foreign('accommodation_inventory_id')->references('id')->on('accommodation_inventories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accommodation_inventory_tours');
    }
}
