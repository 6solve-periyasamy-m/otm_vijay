<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id');
            $table->foreignId('activity_inventory_tour_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_customer_id')->references('id')->on('order_customers')->onDelete('cascade');
            $table->foreign('activity_inventory_tour_id')->references('id')->on('activity_inventory_tours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_activities');
    }
}
