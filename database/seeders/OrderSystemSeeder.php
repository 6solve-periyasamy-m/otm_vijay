<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrdersCustomer;
use App\Models\Quote;
use Illuminate\Database\Seeder;

class OrderSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory()->count(10)->create();
        Quote::factory()->count(10)->create();
        Order::factory()->count(10)->create()->each(function($order) {
            $orderCustomers = OrdersCustomer::factory()->count(10)->make();
            $order->orderCustomers()->saveMany($orderCustomers);
        });
    }
}
