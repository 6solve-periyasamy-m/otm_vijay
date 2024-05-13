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
        Schema::table('supplier_contracts', function (Blueprint $table) {
            $table->dropColumn('price_per_item');
            $table->string('reference_number')->nullable();
            $table->decimal('tax_rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_contracts', function (Blueprint $table) {
            $table->dropColumn('reference_number');
            $table->dropColumn('tax_rate');
            $table->decimal('price_per_item')->nullable();
        });
    }
};
