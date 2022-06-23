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
        Schema::table('quotes', function (Blueprint $table) {
           $table->foreign('lead_traveller_id')->references('id')->on('quote_travellers')->cascadeOnDelete();
           $table->foreign('default_traveller_id')->references('id')->on('quote_travellers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign('quotes_lead_traveller_id_foreign');
            $table->dropForeign('quotes_default_traveller_id_foreign');
        });
    }
};
