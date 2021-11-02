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
            $table->id();
            $table->foreignId('accommodation_id');
            $table->foreignId('room_type_id');
            $table->foreignId('board_type_id');
            $table->dateTime('check_in')->nullable();
            $table->boolean('check_in_time_confirmed')->default(false);
            $table->dateTime('check_out')->nullable();
            $table->boolean('check_out_time_confirmed')->default(false);
            $table->boolean('fit_selectable')->default(true);
            $table->integer('stock');
            $table->double('purchase_price')->nullable();
            $table->double('sales_price')->nullable();
            $table->text('currency')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->onDelete('cascade');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade');
            $table->foreign('board_type_id')->references('id')->on('board_types')->onDelete('cascade');
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
