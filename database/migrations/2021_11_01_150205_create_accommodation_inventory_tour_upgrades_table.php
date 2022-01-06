<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationInventoryTourUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_inventory_tour_upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_id')->constrained('accommodation_inventory_tours')->onDelete('cascade');
            $table->foreignId('upgrade_id')->constrained('accommodation_inventory_tours')->onDelete('cascade');
            $table->text('description');
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
        Schema::dropIfExists('accommodation_inventory_tour_upgrades');
    }
}
