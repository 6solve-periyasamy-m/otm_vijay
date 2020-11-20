<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->nullable();
            $table->dateTime('activity_start_date_time')->nullable();
            $table->dateTime('activity_end_date_time')->nullable();
            $table->tinyInteger('fit_selectable')->nullable();
            $table->integer('ticket_type_id')->nullable();
            $table->integer('stock')->nullable();
            $table->float('purchase_price', 10, 0)->nullable();
            $table->float('sales_price', 10, 0)->nullable();
            $table->string('currency', 10)->nullable();
            $table->integer('activity_type_id')->nullable();
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
        Schema::dropIfExists('activity_inventories');
    }
}
