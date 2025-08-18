<?php

use App\Models\Quote\Quote;
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
        Schema::table('quote_prospects', function (Blueprint $table) {
            $table->foreignId('quote_id')->nullable()->constrained()->cascadeOnDelete();
        });
        // Mark all current prospects as belonging to their quotes
        foreach (Quote::all() as $quote) {
            $quote->leadTraveller->quote_id = $quote->id;
            $quote->leadTraveller->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_prospects', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('quote_id');
        });
    }
};
