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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('lead_traveller_id')->nullable()->constrained('quote_prospects')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference', 64)->nullable();
            $table->decimal('deposit', 12)->nullable();
            $table->boolean('locked')->default(true);
            $table->decimal('single_occupancy_surcharge', 12)->default(0);
            $table->date('final_payment');
            $table->date('date_from');
            $table->date('date_to');
            $table->text('terms');
            $table->text('invoice_footer');
            $table->dateTime('expires')->nullable();
            $table->dateTime('sent')->nullable();
            $table->integer('quote_status')->default(0);
            $table->text('internal_notes')->nullable();
            $table->text('external_notes')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('quotes');
    }
};
