<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tour::factory()->createOne();
        $this->call(LocationTypesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(AirlinesTableSeeder::class);
        $this->call(TravelClassesTableSeeder::class);
        $this->call(AirportsTableSeeder::class);
        $this->call(FlightsTableSeeder::class);
        $this->call(FlightInventoriesTableSeeder::class);
        $this->call(FlightInventoryTourTableSeeder::class);
        $this->call(TransportTypesTableSeeder::class);
        $this->call(RoomTypesTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(ActivityTypesTableSeeder::class);
        $this->call(TicketTypesTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(BoardTypesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
    }
}
