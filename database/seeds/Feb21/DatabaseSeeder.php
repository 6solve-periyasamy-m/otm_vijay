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
    }
}
