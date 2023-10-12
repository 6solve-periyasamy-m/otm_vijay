<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const IDS = [
        63 => 'other',
        1 => 'customer',
        2 => 'accommodation',
        3 => 'activity',
        4 => 'airport',
        5 => 'transport',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('parent')->nullable();
        });

        foreach (static::IDS as $id => $parent) {
            DB::table('addresses')->where('address_parent_id', '=', $id)->update(['parent' => $parent,]);
        }

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('address_parent_id');
        });

        Schema::drop('address_parents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('address_parents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('address_parent_id')->nullable()->constrained();
        });

        foreach (static::IDS as $id => $parent) {
            DB::table('address_parents')->insert(['id' => $id, 'name' => $parent]);
            DB::table('addresses')->where('parent', '=', $parent)->update(['address_parent_id' => $id,]);
        }

        DB::table('addresses')->whereNull('address_parent_id')->update(['address_parent_id' => 63,]);


        Schema::table('addresses', function (Blueprint $table) {
            $table->foreignId('address_parent_id')->nullable(false)->change();
        });
    }
};
