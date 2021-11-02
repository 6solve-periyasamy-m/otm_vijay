<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->boolean('is_converted');
            $table->foreignId('customer_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('tour_id')->index()->constrained()->onDelete('cascade');
            $table->integer('pax_number');
            $table->float('total_quote_value');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('quotes');
    }
}
