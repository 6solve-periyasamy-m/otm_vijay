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
        $this->call(UpdaterDataRowsTableSeeder::class);
        $this->call(UpdaterDataTypesTableSeeder::class);
        $this->call(UpdaterMenusTableSeeder::class);
        $this->call(UpdaterMenuItemsTableSeeder::class);
        $this->call(UpdaterSettingsTableSeeder::class);
    }
}
