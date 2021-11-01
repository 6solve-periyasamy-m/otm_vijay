<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->float('tour_cost', 10, 0)->nullable();
            $table->float('single_occupancy_surcharge', 10, 0)->nullable();
            $table->string('travel_insurer', 255)->nullable();
            $table->string('policy_number', 255)->nullable();
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
        Schema::dropIfExists('orders_customers');
    }
}
