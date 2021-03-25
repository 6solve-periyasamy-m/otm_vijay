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
	$setupUsers = false;
        $this->call(DataTypesTableSeeder::class);
        $this->call(DataRowsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MenuItemsTableSeeder::class);
	if ($setupUsers) {
		$this->call(RolesTableSeeder::class);
		$this->call(PermissionsTableSeeder::class);
		$this->call(PermissionRoleTableSeeder::class);
		$this->call(SettingsTableSeeder::class);
	}
    }
}
