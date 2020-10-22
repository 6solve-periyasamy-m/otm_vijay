<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('accommodation_id')->nullable();
            $table->dateTime('check_in_date_time')->nullable();
            $table->dateTime('check_out_date_time')->nullable();
            $table->integer('room_type_id')->nullable();
            $table->integer('board_type_id')->nullable();
            $table->binary('fit_selectable')->nullable();
            $table->integer('stock')->nullable();
            $table->double('purchase_price')->nullable();
            $table->double('sales_price')->nullable();
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
        Schema::dropIfExists('accommodation_inventories');
    }
}
