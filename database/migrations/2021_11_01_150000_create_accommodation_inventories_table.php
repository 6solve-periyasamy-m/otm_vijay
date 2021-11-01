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
            $table->foreignId('accommodation_id')->index();
            $table->integer('room_type_id');
            $table->integer('board_type_id');
            $table->dateTime('check_in')->nullable();
            $table->boolean('checked_in')->default(false);
            $table->dateTime('check_out')->nullable();
            $table->boolean('checked_out')->default(false);
            $table->boolean('fit_selectable')->default(true);
            $table->integer('stock');
            $table->double('purchase_price')->nullable();
            $table->double('sales_price')->nullable();
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
        Schema::dropIfExists('accommodation_inventories');
    }
}
