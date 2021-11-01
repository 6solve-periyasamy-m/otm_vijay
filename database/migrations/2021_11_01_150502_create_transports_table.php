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
            $table->id();
            $table->foreignId('transport_type_id');
            $table->foreignId('operator_id');
            $table->foreignId('departure_location_id');
            $table->foreignId('arrival_location_id');
            $table->boolean('is_domestic')->default(true);
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('currency', 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('transport_type_id')->references('id')->on('transport_types')->onDelete('cascade');
            $table->foreign('operator_id')->references('id')->on('operators')->onDelete('cascade');
            $table->foreign('departure_location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('arrival_location_id')->references('id')->on('locations')->onDelete('cascade');
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
