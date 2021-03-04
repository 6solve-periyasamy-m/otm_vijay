<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_id' => 1,
                'name' => 'Charlotte Redding',
                'email' => 'clr@octopustravelmatrix.com',
                'avatar' => 'users/January2021/IjIdKkWNr53Tn7sHvELc.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$SD0q0T14idtLT6wkY11Zp.AqP65CEEdLjPedK/x8Ra/tOq4eWCYO2',
                'remember_token' => 'qWByKTG4Dnm7JmSdkdfMORjTMuL81WYSRptJz8MxJ8WVrnY6nzPChpJGm4ee',
                'settings' => '{"locale":"en"}',
                'created_at' => '2021-01-04 18:48:38',
                'updated_at' => '2021-01-15 15:23:08',
            ),
            1 => 
            array (
                'id' => 2,
                'role_id' => 1,
                'name' => 'Jon Redding',
                'email' => 'jsr@octopustravelmatrix.com',
                'avatar' => 'users/default.png',
                'email_verified_at' => NULL,
                'password' => '$2y$10$FliBKN7DQxEERe3fyvIUju1qXw2We5eguCmUouMloKNLd2x7/NJSm',
                'remember_token' => NULL,
                'settings' => '{"locale":"en"}',
                'created_at' => '2021-01-15 15:12:25',
                'updated_at' => '2021-01-15 15:13:14',
            ),
            2 => 
            array (
                'id' => 3,
                'role_id' => 1,
                'name' => 'Nicholas Alexander',
                'email' => 'work@sfsw.net',
                'avatar' => 'users/January2021/Y4d0o9fBQ9rHv93uQlgw.jpg',
                'email_verified_at' => NULL,
                'password' => '$2y$10$zIIrC1pSkoZ8LgGW4dkbourezJ2uvjGkqKPE/nfGhQR2m0cxVqXpK',
                'remember_token' => NULL,
                'settings' => '{"locale":"en"}',
                'created_at' => '2021-01-28 09:01:17',
                'updated_at' => '2021-01-28 09:09:32',
            ),
        ));
        
        
    }
}
