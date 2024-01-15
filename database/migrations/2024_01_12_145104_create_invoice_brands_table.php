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
        Schema::create('invoice_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('website');
            $table->string('email');
            $table->string('telephone');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('town');
            $table->string('region');
            $table->string('country');
            $table->string('postcode');
            $table->string('vat_code');
            $table->string('logo');
            $table->string('header_image')->nullable();
            $table->string('footer_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_brands');
    }
};
