<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('quote_id')->nullable();
            $table->integer('tour_id');
            $table->integer('lead_booker_id')->nullable();
            $table->string('booking_reference');
            $table->dateTime('ordered_on');
            $table->text('internal_notes')->nullable();
            $table->text('external_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('token', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
