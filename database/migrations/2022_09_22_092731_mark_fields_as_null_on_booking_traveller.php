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
        Schema::table('booking_travellers', function (Blueprint $table) {
            $table->foreignId('room_type_id')->nullable()->change();
            $table->integer('group_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_travellers', function (Blueprint $table) {
            $table->foreignId('room_type_id')->nullable(false)->change();
            $table->integer('group_id')->nullable(false)->change();
        });
    }
};
