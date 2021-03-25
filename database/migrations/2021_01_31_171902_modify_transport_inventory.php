<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTransportInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_inventories', function(Blueprint $table) {
            // $table->boolean('arrival_confirmed')->default(false);
            // $table->boolean('departure_confirmed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_inventories', function(Blueprint $table) {
            // $table->dropColumn('arrival_confirmed');
            // $table->dropColumn('departure_confirmed');
        });
    }
}
