<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('region_id');
            $table->string('title', 255)->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->date('audit_date')->nullable();
            $table->text('address')->nullable();
            $table->binary('archive_status')->nullable();
            $table->string('currency', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accommodations');
    }
}
