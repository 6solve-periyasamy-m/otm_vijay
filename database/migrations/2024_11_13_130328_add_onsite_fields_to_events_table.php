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
        Schema::table('events', function (Blueprint $table) {
            $table->string('onsite_name')->nullable();
            $table->string('onsite_email')->nullable();
            $table->string('onsite_phone')->nullable();
            $table->longText('final_terms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('onsite_name');
            $table->dropColumn('onsite_email');
            $table->dropColumn('onsite_phone');
            $table->dropColumn('final_terms');
        });
    }
};
