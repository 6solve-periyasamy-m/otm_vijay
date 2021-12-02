<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 190)->unique();
            $table->string('avatar', 255)->nullable()->default('images/exampleavatar.jpg');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->integer('customer_id')->nullable(); // Store the ID only for customers. TODO: Better solution maybe?
            $table->rememberToken();
            $table->text('settings')->nullable();
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
        Schema::dropIfExists('users');
    }
}
