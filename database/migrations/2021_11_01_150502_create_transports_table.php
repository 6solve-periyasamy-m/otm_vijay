<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transport_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained()->onDelete('cascade');
            $table->foreignId('departure_address_id')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('arrival_address_id')->constrained('addresses')->onDelete('cascade');
            $table->boolean('is_domestic')->default(true);
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('currency', 5)->nullable();
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
        Schema::dropIfExists('transports');
    }
}
