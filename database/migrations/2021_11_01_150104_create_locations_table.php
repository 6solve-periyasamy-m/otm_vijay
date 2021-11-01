<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id');
            $table->foreignId('location_type_id');
            $table->string('name', 255);
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('location_type_id')->references('id')->on('location_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
