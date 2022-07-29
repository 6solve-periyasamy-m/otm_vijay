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
        Schema::create('sent_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('sent');
            $table->string('recipient');
            $table->integer('travelling');
            $table->integer('paying');
            $table->json('data');
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
        Schema::dropIfExists('sent_quotes');
    }
};
