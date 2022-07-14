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
        Schema::drop('order_merchandises');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('order_merchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('merchandise_id')->constrained()->onDelete('cascade');
            $table->decimal('cost', 12);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
