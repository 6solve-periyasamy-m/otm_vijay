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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->string('booking_reference');
            $table->integer('invoice_number');
            $table->dateTime('generated');
            $table->text('invoice_footer')->nullable();
            $table->text('order_notes')->nullable();
            $table->foreignId('invoice_brand_id')->constrained();
            $table->decimal('total_cost', 12);
            $table->decimal('total_paid', 12);
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
        Schema::dropIfExists('invoices');
    }
};
