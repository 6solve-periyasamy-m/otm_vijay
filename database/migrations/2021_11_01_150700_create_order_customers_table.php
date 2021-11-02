<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->float('tour_cost', 10, 0)->nullable();
            $table->float('single_occupancy_surcharge', 10, 0)->nullable();
            $table->string('travel_insurer', 255)->nullable();
            $table->string('policy_number', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_customers');
    }
}
