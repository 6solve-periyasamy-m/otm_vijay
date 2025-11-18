<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accommodation_inventory_tours', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('activity_inventory_tours', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('flight_inventory_tours', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('transport_inventory_tours', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('merchandise_inventory_tours', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodation_inventory_tours', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('activity_inventory_tours', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('flight_inventory_tours', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('transport_inventory_tours', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('merchandise_inventory_tours', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
    }
};
