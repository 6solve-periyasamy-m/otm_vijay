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
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('transport_occupancy_id')->nullable();
            $table->foreign('transport_occupancy_id')
                  ->references('id')->on('transport_occupancy')
                  ->onDelete('cascade');
            $table->index('transport_occupancy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->dropForeign(['transport_occupancy_id']);
            $table->dropColumn('transport_occupancy_id');
        });
    }
};
