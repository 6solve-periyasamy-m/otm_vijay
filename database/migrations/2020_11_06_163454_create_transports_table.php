<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transport_type_id');
            $table->integer('operator_id');
            $table->integer('departure_location_id');
            $table->boolean('is_domestic')->default(true);
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('currency', 5)->nullable();
            $table->integer('arrival_location_id')->nullable();
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
        Schema::dropIfExists('transports');
    }
}
