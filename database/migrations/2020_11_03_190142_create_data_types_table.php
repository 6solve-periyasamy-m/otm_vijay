<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->string('display_name_singular', 255);
            $table->string('display_name_plural', 255);
            $table->string('icon', 255)->nullable();
            $table->string('model_name', 255)->nullable();
            $table->string('policy_name', 255)->nullable();
            $table->string('controller', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->boolean('generate_permissions')->default(0);
            $table->tinyInteger('server_side')->default(0);
            $table->text('details')->nullable();
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
        Schema::dropIfExists('data_types');
    }
}
