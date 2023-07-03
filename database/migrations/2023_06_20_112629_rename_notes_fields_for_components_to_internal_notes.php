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
        Schema::table('accommodations', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable()->default(null);
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable()->default(null);
        });
        Schema::table('flights', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable()->default(null);
        });
        Schema::table('merchandises', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable()->default(null);
        });
        Schema::table('transports', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodations', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('flights', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('merchandises', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('transports', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
    }
};
