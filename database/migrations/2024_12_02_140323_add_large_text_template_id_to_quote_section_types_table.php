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
        Schema::table('quote_section_types', static function (Blueprint $table) {
            $table->foreignId('large_text_template_id')->nullable()->constrained('large_text_templates')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_section_types', static function (Blueprint $table) {
            $table->dropConstrainedForeignId('large_text_template_id');
        });
    }
};
