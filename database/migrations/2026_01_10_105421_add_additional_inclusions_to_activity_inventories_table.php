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
        Schema::table('activity_inventories', function (Blueprint $table) {
            $table->longText('additional_inclusions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_inventories', function (Blueprint $table) {
            $table->dropColumn('additional_inclusions');
        });
    }
};
