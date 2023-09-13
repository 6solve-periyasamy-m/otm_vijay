<?php

use App\Models\Customer\Organization;
use App\Models\Helper\AddressParent;
use App\Models\Location\Address;
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
        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignId('delivery_address_id')->nullable();
            $table->foreignId('billing_address_id')->nullable();
        });
        foreach (Organization::all() as $organization) {
            $organization->delivery_address_id = Address::create(['name' => $organization->name . ' Delivery Address', 'parent' => AddressParent::ORGANIZATION,])->id;
            $organization->billing_address_id = Address::create(['name' => $organization->name . ' Billing Address', 'parent' => AddressParent::ORGANIZATION,])->id;
        }
        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignId('delivery_address_id')->change()->nullable(false)->constrained('addresses')->cascadeOnDelete();
            $table->foreignId('billing_address_id')->change()->nullable(false)->constrained('addresses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->foreignId('delivery_address_id')->nullable(true);
            $table->foreignId('billing_address_id')->nullable(true);
        });

        foreach (Organization::all() as $organization) {
            $organization->deliveryAddress()->delete();
            $organization->billingAddress()->delete();
        }
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_address_id');
            $table->dropConstrainedForeignId('billing_address_id');
        });
    }
};
