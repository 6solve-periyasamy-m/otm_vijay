<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalOrderCustomerNotesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_customers', function (Blueprint $table) {
            $table->longText('internal_notes')->nullable();
            $table->longText('external_notes')->nullable();
            $table->longText('accommodation_notes')->nullable();
            $table->longText('activity_notes')->nullable();
            $table->longText('flight_notes')->nullable();
            $table->longText('transport_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_customers', function (Blueprint $table) {
            $table->dropColumn('internal_notes');
            $table->dropColumn('external_notes');
            $table->dropColumn('accommodation_notes');
            $table->dropColumn('activity_notes');
            $table->dropColumn('flight_notes');
            $table->dropColumn('transport_notes');
        });
    }
}
