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
        Schema::table('payment_reminders', function (Blueprint $table) {
            $table->dropForeign('payment_reminders_order_installment_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_reminders', function (Blueprint $table) {
            $table->foreign('order_installment_id')->on('order_installments')->cascadeOnDelete();
        });
    }
};
