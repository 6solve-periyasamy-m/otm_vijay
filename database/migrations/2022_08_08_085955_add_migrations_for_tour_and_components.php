<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('accommodation_stock_control')->default(0);
            $table->boolean('activity_stock_control')->default(1);
            $table->boolean('flight_stock_control')->default(0);
            $table->boolean('transport_stock_control')->default(0);
            $table->boolean('merchandise_stock_control')->default(0);
        });
        Schema::table('accommodation_inventory_tours', function (Blueprint $table) {
           $table->boolean('stock_control_active')->default(0);
        });
        Schema::table('activity_inventory_tours', function (Blueprint $table) {
           $table->boolean('stock_control_active')->default(1);
        });
        Schema::table('flight_inventory_tours', function (Blueprint $table) {
           $table->boolean('stock_control_active')->default(0);
        });
        Schema::table('transport_inventory_tours', function (Blueprint $table) {
           $table->boolean('stock_control_active')->default(0);
        });
        Schema::table('merchandise_inventory_tours', function (Blueprint $table) {
           $table->boolean('stock_control_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('accommodation_stock_control');
            $table->dropColumn('activity_stock_control');
            $table->dropColumn('flight_stock_control');
            $table->dropColumn('transport_stock_control');
            $table->dropColumn('merchandise_stock_control');
        });
        Schema::table('accommodation_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('stock_control_active');
        });
        Schema::table('activity_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('stock_control_active');
        });
        Schema::table('flight_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('stock_control_active');
        });
        Schema::table('transport_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('stock_control_active');
        });
        Schema::table('merchandise_inventory_tours', function (Blueprint $table) {
            $table->dropColumn('stock_control_active');
        });
    }
};
