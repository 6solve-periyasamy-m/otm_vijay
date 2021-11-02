<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id')->nullable();
            $table->foreignId('flight_inventory_tour_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_customer_id')->references('id')->on('order_customers')->onDelete('cascade');
            $table->foreign('flight_inventory_tour_id')->references('id')->on('flight_inventory_tour')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_flights');
    }
}
