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
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('lead_traveller_id')->nullable()->constrained('quote_prospects')->cascadeOnDelete();
            $table->foreignId('default_traveller_id')->nullable()->constrained('quote_prospects')->cascadeOnDelete();
            $table->string('reference', 64)->nullable();
            $table->decimal('deposit', 12)->nullable();
            $table->dateTime('expires')->nullable();
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
