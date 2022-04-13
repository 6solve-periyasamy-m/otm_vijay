<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->enum('deposit_type', ['fixed', 'percentage', 'full'])->default('fixed');
            $table->float('deposit')->nullable();
            $table->enum('installment_type', ['fixed', 'percentage', 'balance']);
            $table->enum('installment_period', ['monthly', 'quarterly', 'bimonthly', 'weekly'])->default('monthly');
            $table->float('installment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_schedules');
    }
}
