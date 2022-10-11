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
        Schema::table('quote_accommodations', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('quote_activities', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('quote_flights', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('quote_transports', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('quote_merchandises', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price',12)->default(0)->nullable(false)->change();
            $table->decimal('sales_price',12)->default(0)->nullable(false)->change();
            $table->integer('stock')->default(0)->nullable(false)->change();
        });
        Schema::table('activity_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->default(0)->nullable(false)->change();
            $table->decimal('sales_price', 12)->default(0)->nullable(false)->change();
            $table->integer('stock')->default(0)->nullable(false)->change();
        });
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->default(0)->nullable(false)->change();
            $table->decimal('sales_price', 12)->default(0)->nullable(false)->change();
            $table->integer('stock')->default(0)->nullable(false)->change();
        });
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->default(0)->nullable(false)->change();
            $table->decimal('sales_price', 12)->default(0)->nullable(false)->change();
            $table->integer('stock')->default(0)->nullable(false)->change();
        });
        Schema::table('merchandise_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->default(0)->nullable(false)->change();
            $table->decimal('sales_price', 12)->default(0)->nullable(false)->change();
            $table->integer('stock')->default(0)->nullable(false)->change();
        });
        Schema::table('accommodation_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('activity_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('flight_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('transport_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
        Schema::table('merchandise_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->default(0)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_accommodations', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('quote_activities', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('quote_flights', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('quote_transports', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('quote_merchandises', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->nullable()->default(null)->change();
            $table->decimal('sales_price', 12)->nullable()->default(null)->change();
            $table->decimal('stock')->nullable()->default(null)->change();
        });
        Schema::table('activity_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->nullable()->default(null)->change();
            $table->decimal('sales_price', 12)->nullable()->default(null)->change();
            $table->decimal('stock')->nullable()->default(null)->change();
        });
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->nullable()->default(null)->change();
            $table->decimal('sales_price', 12)->nullable()->default(null)->change();
            $table->decimal('stock')->nullable()->default(null)->change();
        });
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->nullable()->default(null)->change();
            $table->decimal('sales_price', 12)->nullable()->default(null)->change();
            $table->decimal('stock')->nullable()->default(null)->change();
        });
        Schema::table('merchandise_inventories', function (Blueprint $table) {
            $table->decimal('purchase_price', 12)->nullable()->default(null)->change();
            $table->decimal('sales_price', 12)->nullable()->default(null)->change();
            $table->decimal('stock')->nullable()->default(null)->change();
        });
        Schema::table('accommodation_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('activity_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('flight_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('transport_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
        Schema::table('merchandise_inventory_tours', function (Blueprint $table) {
            $table->decimal('tour_sales_price', 12)->nullable()->default(null)->change();
        });
    }
};
