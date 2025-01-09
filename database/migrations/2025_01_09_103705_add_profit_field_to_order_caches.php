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
        Schema::table('order_caches', function (Blueprint $table) {
            $table->decimal('profit', 10, 2)->nullable()->after('cost_to_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_caches', function (Blueprint $table) {
            $table->dropColumn('profit');
        });
    }
};
