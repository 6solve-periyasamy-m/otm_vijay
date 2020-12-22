<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name', 255);
            $table->unsignedBigInteger('row_id')->index();
<<<<<<< Updated upstream
            $table->string('event', 255)->index();
=======
            $table->string('event', 120)->index();
>>>>>>> Stashed changes
            $table->text('before')->nullable();
            $table->text('after')->nullable();
            $table->string('ip_address', 255)->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedBigInteger('user_id')->index();
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
        Schema::dropIfExists('model_log');
    }
}
