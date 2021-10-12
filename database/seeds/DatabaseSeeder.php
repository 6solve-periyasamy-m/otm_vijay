<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LocationTypesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(AirportsTableSeeder::class);
        $this->call(FlightsTableSeeder::class);
        $this->call(FlightInventoryTourTableSeeder::class);
        $this->call(FlightInventoriesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(TransportTypesTableSeeder::class);
        $this->call(RoomTypesTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(AirlinesTableSeeder::class);
        $this->call(TravelClassesTableSeeder::class);
    }
}
