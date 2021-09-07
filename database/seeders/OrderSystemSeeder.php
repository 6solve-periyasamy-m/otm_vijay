<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrdersCustomer;
use App\Models\Payment;
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
            $orderCustomers = OrdersCustomer::factory()->make(['is_lead_booker' => true]);
            $order->orderCustomers()->save($orderCustomers);
            $orderCustomers = OrdersCustomer::factory()->count(2)->make(['is_lead_booker' => false]);
            $order->orderCustomers()->saveMany($orderCustomers);
            $payments = Payment::factory()->count(5)->make();
            $order->payments()->saveMany($payments);
        });
    }
}
