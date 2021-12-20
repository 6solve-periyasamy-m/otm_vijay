<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('departure_airport_id')->nullable()->constrained('airports')->onDelete('cascade');
            $table->foreignId('arrival_airport_id')->nullable()->constrained('airports')->onDelete('cascade');
            $table->boolean('is_domestic')->default(false);
            $table->string('image_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->date('available_after')->nullable();
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
        Schema::dropIfExists('flights');
    }
}
