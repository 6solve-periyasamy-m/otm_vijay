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
        Schema::dropIfExists('invoices');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('number');
            $table->dateTime('generated');
            $table->longText('customers');
            $table->longText('groups');
            $table->longText('adjustments');
            $table->longText('payments');
            $table->longText('installments');
            $table->text('footer')->nullable();
            $table->decimal('total_cost', 12);
            $table->timestamps();
            $table->softDeletes();
            $table->text('notes')->nullable();
        });
    }
};
