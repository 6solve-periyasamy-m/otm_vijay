<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;
use App\Models\Activity;
use App\Models\ActivityInventory;
use App\Models\ActivityInventoryTour;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrdersCustomer;
use App\Models\Payment;
use App\Models\Quote;
use Illuminate\Database\Seeder;

class OrderSystemSeeder extends Seeder
{
    protected $seedCount = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory()->count($this->seedCount)->create();
        Quote::factory()->count($this->seedCount)->create();
        Order::factory()->count($this->seedCount)->create()->each(function($order) {
            $orderCustomers = OrdersCustomer::factory()->make(['is_lead_booker' => true]);
            $order->orderCustomers()->save($orderCustomers);
            $orderCustomers = OrdersCustomer::factory()->count($this->seedCount-1)->make(['is_lead_booker' => false]);
            $order->orderCustomers()->saveMany($orderCustomers);
            $payments = Payment::factory()->count($this->seedCount)->make();
            $order->payments()->saveMany($payments);
        });
        Accommodation::factory()->count($this->seedCount)->create()->each(function($accommodation) {
            $inventories = AccommodationInventory::factory()->count($this->seedCount)->make();
            $accommodation->inventory()->saveMany($inventories);
            $inventories->each(function($inventory) {
                $tourInventories = AccommodationInventoryTour::factory()->count($this->seedCount)->make();
                $inventory->tourComponents()->saveMany($tourInventories);
            });
        });
        Activity::factory()->count($this->seedCount)->create()->each(function($activity) {
            $inventories = ActivityInventory::factory()->count($this->seedCount)->make();
            $activity->activityInventory()->saveMany($inventories);
            $inventories->each(function($inventory) {
                $tourInventories = ActivityInventoryTour::factory()->count($this->seedCount)->make();
                $inventory->tourComponents()->saveMany($tourInventories);
            });
        });
    }
}
