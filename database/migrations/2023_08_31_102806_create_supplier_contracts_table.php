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
        Schema::create('supplier_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_number')->nullable();
            $table->foreignId('currency_id')->constrained();
            $table->decimal('agreed_exchange', 8, 8);
            $table->decimal('total_cost', 12);
            $table->decimal('price_per_item')->nullable();
            $table->boolean('confirmed')->default(true);
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('supplier_contracts');
    }
};
