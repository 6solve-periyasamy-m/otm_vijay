<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportInventoryTourUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_inventory_tour_upgrades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_id')->constrained('transport_inventory_tours')->onDelete('cascade');
            $table->foreignId('upgrade_id')->constrained('transport_inventory_tours')->onDelete('cascade');
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
        Schema::dropIfExists('transport_inventory_tour_upgrades');
    }
}
