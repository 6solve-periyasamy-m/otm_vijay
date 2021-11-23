<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customers')->delete();
        
        \DB::table('customers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'email_address' => 'celeste.gateley@gmail.com',
                'password' => '$2y$10$TOTH2y1jwJPswqNhYlk/..U.M882h41A7Y4l3OK4OiD1QBBYaAfem',
                'login_token' => NULL,
                'gender' => 'Female',
                'title' => 'Miss',
                'first_name' => 'Celeste',
                'middle_names' => NULL,
                'last_name' => 'Gateley',
                'date_of_birth' => '1998-10-31',
                'mobile_number' => '07727612421',
                'other_phone_number' => '01274871393',
                'home_address_id' => 6,
                'billing_address_id' => 7,
                'emergency_contact_name' => 'Suzanne Gateley',
                'emergency_contact_relationship' => 'Mother',
                'emergency_contact_telephone' => '07827827898',
                'passport_first_name' => 'Celeste',
                'passport_middle_name' => NULL,
                'passport_last_name' => 'Gateley',
                'passport_number' => '123456',
                'passport_issue_date' => '2021-11-22',
                'passport_expiry_date' => '2021-11-26',
                'passport_country_of_issue' => NULL,
                'loyalty_number' => NULL,
                'profile_picture' => 'images/exampleavatar.jpg',
                't_shirt_size_id' => NULL,
                'hat_size_id' => NULL,
                'notes' => NULL,
                'created_at' => '2021-11-22 13:50:17',
                'updated_at' => '2021-11-22 13:50:17',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
