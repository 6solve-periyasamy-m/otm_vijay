<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAccommodationInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accommodation_inventories', function(Blueprint $table) {
            $table->addColumn('boolean', 'checkin_confirmed')->default(false);
            $table->addColumn('boolean', 'checkout_confirmed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodation_inventories', function(Blueprint $table) {
            $table->dropColumn('checkin_confirmed');
            $table->dropColumn('checkout_confirmed');
        });
    }
}
