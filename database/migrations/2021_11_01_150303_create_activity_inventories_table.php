<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->index()->constrained()->onDelete('cascade');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->tinyInteger('fit_selectable')->nullable();
            $table->foreignId('ticket_type_id')->index()->constrained()->onDelete('cascade');
            $table->integer('stock');
            $table->double('purchase_price');
            $table->double('sales_price');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('activity_inventories');
    }
}
