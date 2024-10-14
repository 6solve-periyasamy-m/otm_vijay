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
        Schema::table('tour_categories', static function (Blueprint $table) {
            $table->integer('display_mode_type')->nullable();
            $table->string('display_mode_color')->nullable()->after('display_mode_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_categories', static function (Blueprint $table) {
            $table->dropColumn('display_mode_type');
            $table->dropColumn('display_mode_color');
        });
    }
};
