<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityInventoryTourUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_inventory_tour_upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_id')->constrained('activity_inventory_tours')->onDelete('cascade');
            $table->foreignId('upgrade_id')->constrained('activity_inventory_tours')->onDelete('cascade');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_inventory_tour_upgrades');
    }
}
