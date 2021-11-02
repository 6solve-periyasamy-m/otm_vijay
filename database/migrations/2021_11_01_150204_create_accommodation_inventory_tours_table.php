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
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->foreignId('accommodation_inventory_id')->constrained()->onDelete('cascade');
            $table->float('tour_sales_price', 10, 0)->nullable();
            $table->enum('tour_component_type', ['Included', 'Add-on', 'Upgrade'])->default('Included');
            $table->string('booking_policy', 12)->default('overbook');
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
        Schema::dropIfExists('accommodation_inventory_tours');
    }
}
