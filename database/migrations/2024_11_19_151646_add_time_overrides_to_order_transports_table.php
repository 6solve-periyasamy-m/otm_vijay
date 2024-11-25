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
        Schema::table('order_transports', static function (Blueprint $table) {
            $table->string('departs_at_time_override')->nullable();
            $table->string('arrives_at_time_override')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_transports', static function (Blueprint $table) {
            $table->dropColumn('departs_at_time_override');
            $table->dropColumn('arrives_at_time_override');
        });
    }
};
