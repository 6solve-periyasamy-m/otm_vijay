<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_booker_id')->nullable();
            $table->string('booking_reference')->nullable();
            $table->decimal('deposit', 12)->nullable();
            $table->dateTime('ordered_on');
            $table->boolean('cancelled')->default(false);
            $table->text('internal_notes')->nullable();
            $table->text('external_notes')->nullable();
            $table->text('invoice_footer')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('token', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
