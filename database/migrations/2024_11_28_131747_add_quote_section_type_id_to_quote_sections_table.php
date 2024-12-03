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
            $table->foreignId('quote_section_type_id')->nullable()->constrained('quote_section_types')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_sections', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('quote_section_type_id');
        });
    }
};
