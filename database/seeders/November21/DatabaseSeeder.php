<?php

namespace Database\Seeders\November21;

use App\Models\AddressParent;
use Illuminate\Database\Seeder;
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
        //$this->call(EventsTableSeeder::class);
        //Tour::factory()->createOne();
        $this->call(LocationTypesTableSeeder::class);
        Artisan::call('countries:update');
        //$this->call(RegionsTableSeeder::class);
        //$this->call(LocationsTableSeeder::class);
        $this->call(AirlinesTableSeeder::class);
        $this->call(TravelClassesTableSeeder::class);
        //$this->call(AirportsTableSeeder::class);
        //$this->call(FlightsTableSeeder::class);
        //$this->call(FlightInventoriesTableSeeder::class);
        //$this->call(FlightInventoryTourTableSeeder::class);
        $this->call(TransportTypesTableSeeder::class);
        $this->call(RoomTypesTableSeeder::class);
        $this->call(ActivityTypesTableSeeder::class);
        $this->call(TicketTypesTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(BoardTypesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
    }
}
