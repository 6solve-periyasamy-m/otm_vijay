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
        Schema::table('accommodation_inventories', static function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
        Schema::table('activity_inventories', static function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
        Schema::table('flight_inventories', static function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
        Schema::table('transport_inventories', static function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodation_inventories', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::table('activity_inventories', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::table('flight_inventories', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::table('transport_inventories', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
    }
};
