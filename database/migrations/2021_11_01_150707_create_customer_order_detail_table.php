<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CustomerOrderDetail;

class CreateCustomerOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_order_details', function(Blueprint $table) {
            $table->id();
            $table->integer('order_customer_id')->index();
            $table->string('type')->index(); // flight / accommodation / activity / transport
            $table->integer('inventory_tour_id')->index(); // e.g. flight_inventory_tour_id
            $table->datetime('date_time');
            $table->string('status');
            $table->string('reference');
            $table->boolean('addon')->default(false);
            $table->decimal('cost', $precision = 8, $scale = 2);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            // $table->foreign('customers_orders_id')->references('id')->on('order_customers');
            // $table->foreign('order_id')->references('id')->on('orders');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_order_details');
    }
}
