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
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->renameColumn('ticket_number', 'transport_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_inventories', function (Blueprint $table) {
            $table->renameColumn('transport_number', 'ticket_number');
        });
    }
};
