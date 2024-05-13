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
        Schema::create('supplier_contract_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_contract_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('due');
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
        Schema::dropIfExists('supplier_contract_installments');
    }
};
