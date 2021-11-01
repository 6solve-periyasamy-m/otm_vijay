<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id');
            $table->foreignId('accommodation_inventory_tour_id');
            $table->foreignId('share_with_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_customer_id')->references('id')->on('orders_customers')->onDelete('cascade');
            $table->foreign('accommodation_inventory_tour_id')->references('id')->on('accommodation_inventory_tours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_accommodations');
    }
}
