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
        Schema::create('order_merchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('merchandise_inventory_tour_id')->constrained()->cascadeOnDelete();
            $table->decimal('cost', 12);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_merchandises');
    }
};
