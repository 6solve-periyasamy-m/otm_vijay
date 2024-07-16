<?php

use App\Models\Order\Payment\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (PaymentMethod::whereNotNull('deleted_at')->get() as $paymentMethod) {
            if ($paymentMethod->payments()->count() > 0) {
                $paymentMethod->restore();
            } else {
                $paymentMethod->forceDelete();
            }
        }
        Schema::table('payment_methods', static function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', static function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
