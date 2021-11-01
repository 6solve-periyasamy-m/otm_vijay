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
            $table->id();
            $table->integer('activity_id')->index();
            $table->dateTime('activity_start_date_time')->nullable();
            $table->dateTime('activity_end_date_time')->nullable();
            $table->tinyInteger('fit_selectable')->nullable();
            $table->integer('ticket_type_id')->index();
            $table->integer('stock');
            $table->double('purchase_price');
            $table->double('sales_price');
            $table->string('currency', 10)->default('GBP');
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
