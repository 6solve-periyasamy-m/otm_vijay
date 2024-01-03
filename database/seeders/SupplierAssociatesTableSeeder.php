<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SupplierAssociatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('supplier_associates')->delete();
        
        \DB::table('supplier_associates')->insert(array (
            0 => 
            array (
                'id' => 4,
                'supplier_id' => 1,
                'name' => 'Abigail Crowley',
                'email' => 'acrowley@suppliers-r-us.com',
                'primary_phone' => '+441274956264',
                'alternative_phone' => '+447854652231',
                'job_title' => 'VP Sales',
                'notes' => 'Alternative number for emergencies only',
                'created_at' => '2023-08-29 12:34:18',
                'updated_at' => '2023-08-29 12:34:18',
            ),
        ));
        
        
    }
}