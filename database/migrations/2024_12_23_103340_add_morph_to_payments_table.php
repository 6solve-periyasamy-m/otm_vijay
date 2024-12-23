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
            $table->renameColumn('customer_id', 'payer_i d');
            $table->string('payer_type')->nullable();
        });
        // Any payments before this are assumed to be made by customers
        DB::table('payments')->whereNotNull('payer_id')->update(['payer_type' => \App\Models\Customer\Customer::class]);
        Schema::table('payments', static function (Blueprint $table) {
            $table->string('payer_type')->nullable(false)->change();
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
