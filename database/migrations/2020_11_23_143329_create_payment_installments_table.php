<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->integer('is_deposit');
            $table->date('due_date');
            $table->float('value', 10, 0);
            $table->integer('value_type');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('payment_schedule_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_installments');
    }
}
