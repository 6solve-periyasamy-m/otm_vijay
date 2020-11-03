<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('flight_id')->nullable();
            $table->dateTime('check_in_date_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->string('flight_number', 255)->nullable();
            $table->integer('travel_class_id')->nullable();
            $table->tinyInteger('fit_selectable')->nullable();
            $table->integer('stock')->nullable();
            $table->float('purchase_price', 10, 0)->nullable();
            $table->float('sales_price', 10, 0)->nullable();
            $table->text('currency')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('flight_inventories');
    }
}
