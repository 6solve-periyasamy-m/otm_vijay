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
        Schema::table('quote_accommodations', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
        });
        Schema::table('quote_activities', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
        });
        Schema::table('quote_flights', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
        });
        Schema::table('quote_transports', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
        });
        Schema::table('quote_merchandises', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_accommodations', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
        Schema::table('quote_activities', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
        Schema::table('quote_flights', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
        Schema::table('quote_transports', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
        Schema::table('quote_merchandises', function (Blueprint $table) {
            $table->integer('quantity')->nullable(false)->change();
        });
    }
};
