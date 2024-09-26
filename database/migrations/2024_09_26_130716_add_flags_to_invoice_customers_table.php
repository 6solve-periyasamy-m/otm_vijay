<?php

use App\Models\Order\Invoice\InvoiceCustomer;
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
        Schema::table('invoice_customers', function (Blueprint $table) {
            $table->boolean('travelling')->default(true);
            $table->boolean('paying')->default(true);
        });
        foreach (InvoiceCustomer::all() as $iC) {
            if ($iC->billables()->count() === 0) {
                $iC->travelling = false;
                $iC->paying = false;
                $iC->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_customers', function (Blueprint $table) {
            $table->dropColumn('travelling');
            $table->dropColumn('paying');
        });
    }
};
