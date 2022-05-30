<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IsInventoryTourBookableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accommodation_inventory_tours', function (Blueprint $table) {
            $table->boolean('is_bookable')->default(true);
        });
        Schema::table('activity_inventory_tours', function (Blueprint $table) {
            $table->boolean('is_bookable')->default(true);
        });
        Schema::table('flight_inventory_tours', function (Blueprint $table) {
            $table->boolean('is_bookable')->default(true);
        });
        Schema::table('transport_inventory_tours', function (Blueprint $table) {
            $table->boolean('is_bookable')->default(true);
        });
        Schema::table('merchandises', function (Blueprint $table) {
            $table->boolean('is_bookable')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodation_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('is_bookable');
        });
        Schema::table('activity_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('is_bookable');
        });
        Schema::table('flight_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('is_bookable');
        });
        Schema::table('transport_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('is_bookable');
        });
        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropColumn('is_bookable');
        });
    }
}
