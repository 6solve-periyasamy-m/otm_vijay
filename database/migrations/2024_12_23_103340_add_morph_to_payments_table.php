<?php

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
        Schema::table('payments', static function (Blueprint $table) {
            $table->dropForeign('payments_customer_id_foreign');
            $table->renameColumn('customer_id', 'payer_id');
            $table->string('payer_type')->default('App\Models\Customer\Customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payer_type');
            $table->renameColumn('payer_id', 'customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
};
