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
        Schema::table('quote_accommodations', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('quote_activities', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('quote_flights', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('quote_transports', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
        Schema::table('quote_merchandises', static function (Blueprint $table) {
            $table->integer('document_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_accommodations', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('quote_activities', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('quote_flights', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('quote_transports', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
        Schema::table('quote_merchandises', static function (Blueprint $table) {
            $table->dropColumn('document_order');
        });
    }
};
