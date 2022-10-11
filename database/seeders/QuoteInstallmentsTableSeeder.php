<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuoteInstallmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_installments')->delete();
        
        \DB::table('quote_installments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => 1,
                'due_on' => '2021-08-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'quote_id' => 1,
                'due_on' => '2021-11-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            2 => 
            array (
                'id' => 3,
                'quote_id' => 1,
                'due_on' => '2022-02-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            3 => 
            array (
                'id' => 4,
                'quote_id' => 1,
                'due_on' => '2022-05-10',
                'amount' => '1084.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            4 => 
            array (
                'id' => 5,
                'quote_id' => 2,
                'due_on' => '2022-10-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            5 => 
            array (
                'id' => 6,
                'quote_id' => 2,
                'due_on' => '2022-12-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            6 => 
            array (
                'id' => 7,
                'quote_id' => 2,
                'due_on' => '2023-02-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            7 => 
            array (
                'id' => 8,
                'quote_id' => 2,
                'due_on' => '2023-05-10',
                'amount' => '1084.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            8 => 
            array (
                'id' => 9,
                'quote_id' => 3,
                'due_on' => '2022-10-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            9 => 
            array (
                'id' => 10,
                'quote_id' => 3,
                'due_on' => '2022-12-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            10 => 
            array (
                'id' => 11,
                'quote_id' => 3,
                'due_on' => '2023-02-01',
                'amount' => '692.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            11 => 
            array (
                'id' => 12,
                'quote_id' => 3,
                'due_on' => '2023-05-10',
                'amount' => '1084.00',
                'percentage' => 0,
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
        ));
        
        
    }
}