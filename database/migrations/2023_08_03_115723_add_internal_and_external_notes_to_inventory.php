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
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable();
        });
        Schema::table('activity_inventories', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable();
        });
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable();
        });
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable();
        });
        Schema::table('merchandise_inventories', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->text('external_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('activity_inventories', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('flight_inventories', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
        Schema::table('merchandise_inventories', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
        });
    }
};
