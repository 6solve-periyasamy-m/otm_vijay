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
            $table->foreignId('accommodation_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('board_type_id')->constrained()->onDelete('cascade');
            $table->dateTime('check_in')->nullable();
            $table->boolean('check_in_time_confirmed')->default(false);
            $table->dateTime('check_out')->nullable();
            $table->boolean('check_out_time_confirmed')->default(false);
            $table->boolean('fit_selectable')->default(true);
            $table->integer('stock');
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
