<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_type_id')->index()->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('address_id')->index()->constrained()->onDelete('cascade');
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null');
            $table->text('name')->nullable();
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
        Schema::dropIfExists('activities');
    }
}
