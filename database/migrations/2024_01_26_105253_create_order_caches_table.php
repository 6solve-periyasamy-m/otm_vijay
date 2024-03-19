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
        Schema::create('order_caches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->integer('status');
            $table->decimal('commission_amount', 12)->nullable();
            $table->decimal('cost', 12);
            $table->decimal('total_owed', 12);
            $table->date('next_payment_date')->nullable();
            $table->decimal('next_payment_amount', 12)->nullable();
            $table->decimal('next_payment_remaining', 12)->nullable();
            $table->dateTime('cached');
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
        Schema::dropIfExists('order_caches');
    }
};
