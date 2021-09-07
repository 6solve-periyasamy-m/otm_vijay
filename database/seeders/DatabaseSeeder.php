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
        $this->call(DataTypesTableSeeder::class);
        $this->call(DataRowsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MenuItemsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
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
        Tour::factory()->createOne();
        $this->call(CountriesTableSeeder::class);
        $this->call(AirlinesTableSeeder::class);
        $this->call(TravelClassesTableSeeder::class);
    }
}
