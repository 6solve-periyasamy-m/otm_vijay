<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddressFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_country_id_foreign');
        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->change();
            $table->string('address_line_1')->nullable()->change();
            $table->string('postcode')->nullable()->change();
        });
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
