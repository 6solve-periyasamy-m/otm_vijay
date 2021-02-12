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
            $table->integer('payment_schedule_id');
            $table->float('deposit', 10, 2);
            $table->string('period', 20);
            $table->integer('installments');
            $table->float('amount', 10, 2);
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
        Schema::dropIfExists('payment_installments');
    }
}
