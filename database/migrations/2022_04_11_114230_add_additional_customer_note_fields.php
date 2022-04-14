<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalCustomerNoteFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('notes', 'internal_notes');
            $table->longText('external_notes')->nullable();
            $table->longText('dietary_notes')->nullable();
            $table->longText('mobility_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('internal_notes', 'notes');
            $table->dropColumn('external_notes');
            $table->dropColumn('dietary_notes');
            $table->dropColumn('mobility_notes');
        });
    }
}
