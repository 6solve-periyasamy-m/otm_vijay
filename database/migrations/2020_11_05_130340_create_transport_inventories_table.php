<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transport_id');
            $table->integer('travel_class_id');
            $table->dateTime('departs_at')->nullable();
            $table->dateTime('arrival_date_time')->nullable();
            $table->boolean('fit_selectable')->default(true);
            $table->integer('stock')->nullable();
            $table->float('purchase_price', 10, 0)->nullable();
            $table->float('sales_price', 10, 0)->nullable();
            $table->string('currency', 5)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('arrival_confirmed')->default(false);
            $table->boolean('departure_confirmed')->default(false);
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
        Schema::dropIfExists('transport_inventories');
    }
}
