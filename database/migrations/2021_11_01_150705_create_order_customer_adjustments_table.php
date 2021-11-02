<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomerAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_customer_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id');
            $table->float('amount');
            $table->text('reason');
            $table->dateTime('date');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('order_customer_id')->references('id')->on('order_customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_customer_adjustments');
    }
}
