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
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->foreignId('stock_parent_id')->nullable()->constrained('accommodation_inventories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('stock_parent_id');
        });
    }
};
