<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingToAccommodationInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accommodation_inventories', function (Blueprint $table) {
            $table->integer('add_on_price');
            $table->integer('purchase_price');
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
            $table->dropColumn('add_on_price');
            $table->dropColumn('purchase_price');
        });
    }
}
