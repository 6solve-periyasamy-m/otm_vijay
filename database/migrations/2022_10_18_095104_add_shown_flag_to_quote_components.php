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
            $table->boolean('price_shown')->default(false);
        });
        Schema::table('quote_activities', function (Blueprint $table) {
            $table->boolean('price_shown')->default(false);
        });
        Schema::table('quote_flights', function (Blueprint $table) {
            $table->boolean('price_shown')->default(false);
        });
        Schema::table('quote_transports', function (Blueprint $table) {
            $table->boolean('price_shown')->default(false);
        });
        Schema::table('quote_merchandises', function (Blueprint $table) {
            $table->boolean('price_shown')->default(false);
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
            $table->dropColumn('price_shown');
        });
        Schema::table('quote_activities', function (Blueprint $table) {
            $table->dropColumn('price_shown');
        });
        Schema::table('quote_flights', function (Blueprint $table) {
            $table->dropColumn('price_shown');
        });
        Schema::table('quote_transports', function (Blueprint $table) {
            $table->dropColumn('price_shown');
        });
        Schema::table('quote_merchandises', function (Blueprint $table) {
            $table->dropColumn('price_shown');
        });
    }
};
