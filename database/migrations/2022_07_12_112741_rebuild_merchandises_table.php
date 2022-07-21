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
        Schema::drop('merchandises');
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchandise_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->string('notes')->nullable();
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
        Schema::drop('merchandises');
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tour_component_type');
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->string('image_url')->nullable();
            $table->integer('stock');
            $table->decimal('purchase_price', 12);
            $table->decimal('tour_sales_price', 12);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
