<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\AccommodationInventory;
use App\Models\AccommodationInventoryTour;
use App\Models\Activity;
use App\Models\ActivityInventory;
use App\Models\ActivityInventoryTour;
use App\Models\Address;
use App\Models\Customer;
use App\Models\FlightInventoryTour;
use App\Models\Operator;
use App\Models\Order;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\OrderTransport;
use App\Models\Payment;
use App\Models\Quote;
use App\Models\Transport;
use App\Models\TransportInventory;
use App\Models\TransportInventoryTour;
use Illuminate\Database\Seeder;

class OrderSystemSeeder extends Seeder
{
    protected $seedCount = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::factory()->count($this->seedCount)->create();
        Customer::factory()->count($this->seedCount)->create();
        Quote::factory()->count($this->seedCount)->create();
        Order::factory()->count($this->seedCount)->create()->each(function($order) {
            $orderCustomers = OrderCustomer::factory()->count($this->seedCount)->make();
            $order->orderCustomers()->saveMany($orderCustomers);
            $order->lead_booker_id = $orderCustomers->all()[0]->id;
            $order->save();
            $payments = Payment::factory()->count($this->seedCount)->make();
            $order->payments()->saveMany($payments);
        });
        Accommodation::factory()->count($this->seedCount)->create()->each(function($accommodation) {
            $inventories = AccommodationInventory::factory()->count($this->seedCount)->make();
            $accommodation->inventory()->saveMany($inventories);
            $inventories->each(function($inventory) {
                $tourInventories = AccommodationInventoryTour::factory()->count($this->seedCount)->make();
                $inventory->tourComponents()->saveMany($tourInventories);
                $tourInventories->each(function ($tourInventory) {
                   $orders = OrderAccommodation::factory()->count($this->seedCount)->make();
                   $tourInventory->orders()->saveMany($orders);
                });
            });
        });
        Activity::factory()->count($this->seedCount)->create()->each(function($activity) {
            $inventories = ActivityInventory::factory()->count($this->seedCount)->make();
            $activity->activityInventory()->saveMany($inventories);
            $inventories->each(function($inventory) {
                $tourInventories = ActivityInventoryTour::factory()->count($this->seedCount)->make();
                $inventory->tourComponents()->saveMany($tourInventories);
                $tourInventories->each(function ($tourInventory) {
                    $orders = OrderActivity::factory()->count($this->seedCount)->make();
                    $tourInventory->orders()->saveMany($orders);
                });
            });
        });
        Operator::factory()->count($this->seedCount)->create()->each(function($operator) {
           $transports = Transport::factory()->count($this->seedCount)->make();
           $operator->transports()->saveMany($transports);
           $transports->each(function($transport) {
              $inventories = TransportInventory::factory()->count($this->seedCount)->make();
              $transport->transportInventory()->saveMany($inventories);
              $inventories->each(function($inventory) {
                 $tourInventories = TransportInventoryTour::factory()->count($this->seedCount)->make();
                 $inventory->tourComponents()->saveMany($tourInventories);
                  $tourInventories->each(function ($tourInventory) {
                      // Due to how nested this is, only one will be made
                      $orders = OrderTransport::factory()->makeOne();
                      $tourInventory->orders()->save($orders);
                  });
              });
           });
        });
        FlightInventoryTour::all()->each(function($tourInventory) {
            $orders = OrderFlight::factory()->count($this->seedCount)->make();
            $tourInventory->orders()->saveMany($orders);
        });
    }
}
