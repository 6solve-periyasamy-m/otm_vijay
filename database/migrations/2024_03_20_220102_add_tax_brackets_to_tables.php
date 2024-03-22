<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->foreignId('tax_bracket_id')->nullable()->constrained()->nullOnDelete();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('tax_bracket_id')->nullable()->constrained()->nullOnDelete();
        });
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('tax_bracket_id')->nullable()->constrained()->nullOnDelete();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('tax_bracket_id')->nullable()->constrained()->nullOnDelete();
        });
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreignId('tax_bracket_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tax_bracket_id');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tax_bracket_id');
        });
        Schema::table('tours', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tax_bracket_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tax_bracket_id');
        });
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tax_bracket_id');
        });
    }
};
