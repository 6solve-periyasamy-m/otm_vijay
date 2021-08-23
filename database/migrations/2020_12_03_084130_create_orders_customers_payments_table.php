<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCustomersPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_customers_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_customer_id');
            $table->integer('payment_type_id');
            $table->integer('payment_method_id');
            $table->float('payment_amount', 10, 0);
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
        Schema::dropIfExists('orders_customers_payments');
    }
}
