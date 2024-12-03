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
        Schema::table('quote_sections', static function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->integer('quantity')->nullable();
            $table->decimal('purchase_price', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_sections', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
            $table->dropColumn('quantity');
            $table->dropColumn('purchase_price');
        });
    }
};
