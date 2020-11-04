<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id')->nullable()->index('menu_items_menu_id_foreign');
            $table->string('title', 255);
            $table->string('url', 255);
            $table->string('target', 255)->default('_self');
            $table->string('icon_class', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order');
            $table->timestamps();
            $table->string('route', 255)->nullable();
            $table->text('parameters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
