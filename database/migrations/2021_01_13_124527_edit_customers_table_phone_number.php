<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditCustomersTablePhoneNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("customers", function (Blueprint $table) {
            $table->string("mobile_number", 255)->change();
            $table->string("other_phone_number", 255)->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("customers", function (Blueprint $table) {
            $table->integer("mobile_number")->change();
            $table->integer("other_phone_number")->change();
        });
    }
}
