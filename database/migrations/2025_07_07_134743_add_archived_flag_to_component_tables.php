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
        Schema::table('accommodations', static function (Blueprint $table) {
            $table->boolean('archived')->default(false);
        });
        Schema::table('activities', static function (Blueprint $table) {
            $table->boolean('archived')->default(false);
        });
        Schema::table('flights', static function (Blueprint $table) {
            $table->boolean('archived')->default(false);
        });
        Schema::table('transports', static function (Blueprint $table) {
            $table->boolean('archived')->default(false);
        });
        Schema::table('merchandises', static function (Blueprint $table) {
            $table->boolean('archived')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodations', static function (Blueprint $table) {
            $table->dropColumn('archived');
        });
        Schema::table('activities', static function (Blueprint $table) {
            $table->dropColumn('archived');
        });
        Schema::table('flights', static function (Blueprint $table) {
            $table->dropColumn('archived');
        });
        Schema::table('transports', static function (Blueprint $table) {
            $table->dropColumn('archived');
        });
        Schema::table('merchandises', static function (Blueprint $table) {
            $table->dropColumn('archived');
        });
    }
};
