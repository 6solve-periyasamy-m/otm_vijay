<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id')->nullable();
            $table->foreignId('transport_inventory_tour_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_customer_id')->references('id')->on('orders_customers')->onDelete('cascade');
            $table->foreign('transport_inventory_tour_id')->references('id')->on('transport_inventory_tour')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_transports');
    }
}
