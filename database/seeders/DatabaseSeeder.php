<?php

namespace Database\Seeders;

use App\Models\AddressParent;
use Illuminate\Database\Seeder;
use App\Models\Tour;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (AddressParent::ID_MAP as $key => $value) {
            AddressParent::create(['id' => $key, 'name' => $value]);
        }
        Artisan::call('countries:update');
        $this->call(UserSeeder::class);

        if (config('app.debug')) {
            $this->call(LocationTypesTableSeeder::class);
            $this->call(AddressesTableSeeder::class);
            $this->call(AirlinesTableSeeder::class);
            $this->call(AirportsTableSeeder::class);
            $this->call(BoardTypesTableSeeder::class);
            $this->call(ActivityTypesTableSeeder::class);
            $this->call(EventsTableSeeder::class);

            $this->call(OperatorsTableSeeder::class);
            $this->call(PaymentMethodsTableSeeder::class);
            $this->call(ToursTableSeeder::class);
            $this->call(CustomersTableSeeder::class);
            $this->call(TransportTypesTableSeeder::class);
            $this->call(TravelClassesTableSeeder::class);
            $this->call(RoomTypesTableSeeder::class);
            $this->call(SettingsTableSeeder::class);
            $this->call(TicketTypesTableSeeder::class);

            $this->call(AccommodationsTableSeeder::class);
            $this->call(AccommodationInventoriesTableSeeder::class);
            $this->call(AccommodationInventoryToursTableSeeder::class);

            $this->call(ActivitiesTableSeeder::class);
            $this->call(ActivityInventoriesTableSeeder::class);
            $this->call(ActivityInventoryToursTableSeeder::class);

            $this->call(FlightsTableSeeder::class);
            $this->call(FlightInventoriesTableSeeder::class);
            $this->call(FlightInventoryToursTableSeeder::class);

            $this->call(TransportsTableSeeder::class);
            $this->call(TransportInventoriesTableSeeder::class);
            $this->call(TransportInventoryToursTableSeeder::class);

            $this->call(OrdersTableSeeder::class);
            $this->call(ManualAdjustmentsTableSeeder::class);
            $this->call(OrderCustomerAdjustmentsTableSeeder::class);
            $this->call(OrderAccommodationsTableSeeder::class);
            $this->call(OrderActivitiesTableSeeder::class);
            $this->call(OrderFlightsTableSeeder::class);
            $this->call(OrderTransportsTableSeeder::class);
            $this->call(PaymentInstallmentsTableSeeder::class);
            $this->call(PaymentsTableSeeder::class);
        }
    }
}
