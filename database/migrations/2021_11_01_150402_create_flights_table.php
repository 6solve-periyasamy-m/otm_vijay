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
            $table->id();
            $table->foreignId('airline_id')->nullable();
            $table->foreignId('departure_airport_id')->nullable();
            $table->foreignId('arrival_airport_id')->nullable();
            $table->boolean('is_domestic')->default(false);
            $table->text('notes')->nullable();
            $table->string('currency', 5)->nullable();
            $table->date('available_after')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
            $table->foreign('departure_airport_id')->references('id')->on('airports')->onDelete('cascade');
            $table->foreign('arrival_airport_id')->references('id')->on('airports')->onDelete('cascade');
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
