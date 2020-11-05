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
            $table->integer('transport_id')->nullable();
            $table->dateTime('departure_date_time')->nullable();
            $table->dateTime('arrival_date_time')->nullable();
            $table->tinyInteger('fit_selectable')->nullable();
            $table->integer('stock')->nullable();
            $table->float('purchase_price', 10, 0)->nullable();
            $table->float('sales_price', 10, 0)->nullable();
            $table->string('currency', 255)->nullable();
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
        Schema::dropIfExists('transport_inventories');
    }
}
