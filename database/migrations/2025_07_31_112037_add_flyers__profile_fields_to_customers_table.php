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
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('airline_frequent_flyers_id')->nullable()
                  ->constrained('airline_frequent_flyers')
                  ->cascadeOnDelete();
            $table->string('membership')->nullable()->after('airline_frequent_flyers_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('airline_frequent_flyers_id');
            $table->dropColumn('membership');
        });
    }
};
