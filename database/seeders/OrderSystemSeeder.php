<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
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
        Order::factory()->count(10)->create();
    }
}
