<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airline_id')->nullable();
            $table->integer('departure_airport_id')->nullable();
            $table->date('departure_date')->nullable();
            $table->integer('arrival_airport_id')->nullable();
            $table->date('arrival_date')->nullable();
            $table->tinyInteger('is_domestic')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('is_archived')->nullable();
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
        Schema::dropIfExists('flights');
    }
}
