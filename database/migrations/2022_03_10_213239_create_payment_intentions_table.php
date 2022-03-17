<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentIntentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_intentions', function (Blueprint $table) {
            $table->string('id')->index()->unique()->primary();
            $table->foreignId('customer_id')->constrained()->onDelete('CASCADE');
            $table->string('type');
            $table->string('reference'); // Will either have a booking token, or order booking_reference
            $table->text('data')->nullable(); // Used for more advanced payment intentions (Such as only upgrade/addon after payment recieved)
            $table->boolean('processed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_intentions');
    }
}
