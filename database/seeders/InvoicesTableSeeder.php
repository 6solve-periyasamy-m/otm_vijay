<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvoicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('invoices')->delete();
        
        \DB::table('invoices')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'number' => '1',
                'generated' => '2022-01-23 13:02:34',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 1,
                'number' => '2',
                'generated' => '2022-01-23 13:18:05',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":300,"billables":[{"date":"2022-01-23T01:00:00.000000Z","description":"Stripe: Deposit","cost":300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:18:05',
                'updated_at' => '2022-01-23 13:18:05',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            2 => 
            array (
                'id' => 3,
                'order_id' => 1,
                'number' => '3',
                'generated' => '2022-01-23 13:20:07',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":348,"billables":[{"description":"Manual Adjustment: Discount for Repeat Customer","cost":348}]}',
                'payments' => '{"total_cost":300,"billables":[{"date":"2022-01-23T01:00:00.000000Z","description":"Stripe: Deposit","cost":300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3846.0,
                'created_at' => '2022-01-23 13:20:07',
                'updated_at' => '2022-01-23 13:20:07',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            3 => 
            array (
                'id' => 4,
                'order_id' => 1,
                'number' => '4',
                'generated' => '2022-01-23 13:20:21',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":398,"billables":[{"description":"Manual Adjustment: Discount for Repeat Customer","cost":398}]}',
                'payments' => '{"total_cost":300,"billables":[{"date":"2022-01-23T01:00:00.000000Z","description":"Stripe: Deposit","cost":300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3896.0,
                'created_at' => '2022-01-23 13:20:21',
                'updated_at' => '2022-01-23 13:20:21',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":true},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            4 => 
            array (
                'id' => 5,
                'order_id' => 1,
                'number' => '5',
                'generated' => '2022-01-23 13:20:52',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":-398,"billables":[{"description":"Manual Adjustment: Discount for Repeat Customer","cost":-398}]}',
                'payments' => '{"total_cost":300,"billables":[{"date":"2022-01-23T01:00:00.000000Z","description":"Stripe: Deposit","cost":300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3100.0,
                'created_at' => '2022-01-23 13:20:52',
                'updated_at' => '2022-01-23 13:20:52',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            5 => 
            array (
                'id' => 6,
                'order_id' => 2,
                'number' => '1',
                'generated' => '2022-01-23 13:21:17',
            'customers' => '{"Noemi Rath":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            6 => 
            array (
                'id' => 7,
                'order_id' => 2,
                'number' => '2',
                'generated' => '2022-01-23 13:21:35',
            'customers' => '{"Noemi Rath":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":3498,"billables":[{"date":"2022-01-23T00:00:00.000000Z","description":"BACS: Deposit","cost":3498}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:21:35',
                'updated_at' => '2022-01-23 13:21:35',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":true},{"due":"2020-11-01","amount":692,"paid":true},{"due":"2021-02-01","amount":692,"paid":true},{"due":"2021-05-10","amount":1084,"paid":true}]',
            ),
            7 => 
            array (
                'id' => 8,
                'order_id' => 3,
                'number' => '1',
                'generated' => '2022-01-23 13:25:11',
            'customers' => '{"Imogene Tremblay":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            8 => 
            array (
                'id' => 9,
                'order_id' => 3,
                'number' => '2',
                'generated' => '2022-01-23 13:38:49',
            'customers' => '{"Imogene Tremblay":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]},"Marcella Thompson":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 6996.0,
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2020-08-01","amount":692,"paid":false},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            9 => 
            array (
                'id' => 10,
                'order_id' => 1,
                'number' => '6',
                'generated' => '2022-01-23 13:45:08',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":-398,"billables":[{"description":"Manual Adjustment: Discount for Repeat Customer","cost":-398}]}',
                'payments' => '{"total_cost":1256,"billables":[{"date":"2022-01-23T01:00:00.000000Z","description":"Stripe: Deposit","cost":300},{"date":"2022-01-10T20:22:00.000000Z","description":"Cash: Installment","cost":956}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3100.0,
                'created_at' => '2022-01-23 13:45:08',
                'updated_at' => '2022-01-23 13:45:08',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":true},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            10 => 
            array (
                'id' => 11,
                'order_id' => 1,
                'number' => '7',
                'generated' => '2022-01-23 13:45:23',
            'customers' => '{"Alaina Barton":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":-398,"billables":[{"description":"Manual Adjustment: Discount for Repeat Customer","cost":-398}]}',
                'payments' => '{"total_cost":1325,"billables":[{"date":"2022-01-23T01:00:00.000000Z","description":"Stripe: Deposit","cost":300},{"date":"2022-01-10T20:22:00.000000Z","description":"Cash: Installment","cost":1025}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3100.0,
                'created_at' => '2022-01-23 13:45:23',
                'updated_at' => '2022-01-23 13:45:23',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2020-08-01","amount":692,"paid":true},{"due":"2020-11-01","amount":692,"paid":false},{"due":"2021-02-01","amount":692,"paid":false},{"due":"2021-05-10","amount":1084,"paid":false}]',
            ),
            11 => 
            array (
                'id' => 12,
                'order_id' => 4,
                'number' => '1',
                'generated' => '2022-01-23 13:47:50',
            'customers' => '{"Deron Herman":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2021-08-01","amount":692,"paid":false},{"due":"2021-11-01","amount":692,"paid":false},{"due":"2022-02-01","amount":692,"paid":false},{"due":"2022-05-10","amount":1084,"paid":false}]',
            ),
            12 => 
            array (
                'id' => 13,
                'order_id' => 4,
                'number' => '2',
                'generated' => '2022-01-23 13:48:14',
            'customers' => '{"Deron Herman":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":300,"billables":[{"date":"2022-01-10T00:00:00.000000Z","description":"Stripe: Deposit","cost":300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:48:14',
                'updated_at' => '2022-01-23 13:48:14',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":true},{"due":"2021-08-01","amount":692,"paid":false},{"due":"2021-11-01","amount":692,"paid":false},{"due":"2022-02-01","amount":692,"paid":false},{"due":"2022-05-10","amount":1084,"paid":false}]',
            ),
            13 => 
            array (
                'id' => 14,
                'order_id' => 4,
                'number' => '3',
                'generated' => '2022-01-23 13:48:39',
            'customers' => '{"Deron Herman":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[{"date":"2022-01-10T00:00:00.000000Z","description":"Stripe: Deposit","cost":300},{"date":"2022-01-12T10:00:00.000000Z","description":"Stripe: Refund","cost":-300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:48:39',
                'updated_at' => '2022-01-23 13:48:39',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2021-08-01","amount":692,"paid":false},{"due":"2021-11-01","amount":692,"paid":false},{"due":"2022-02-01","amount":692,"paid":false},{"due":"2022-05-10","amount":1084,"paid":false}]',
            ),
            14 => 
            array (
                'id' => 15,
                'order_id' => 4,
                'number' => '4',
                'generated' => '2022-01-23 13:48:42',
            'customers' => '{"Deron Herman":{"total_cost":3498,"billables":[{"description":"Base Components Include:\\nSignature Lux Hotel by ONOMO Foreshore (29\\/07\\/2022 19:00 to 30\\/07\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (30\\/07\\/2022 19:00 to 01\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (01\\/08\\/2022 19:00 to 02\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (02\\/08\\/2022 19:00 to 03\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO Foreshore (03\\/08\\/2022 19:00 to 04\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (04\\/08\\/2022 19:00 to 05\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (05\\/08\\/2022 19:00 to 06\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (06\\/08\\/2022 19:00 to 07\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nSignature Lux Hotel by ONOMO, Sandton (07\\/08\\/2022 19:00 to 08\\/08\\/2022 09:00) (Double Room, Self-Catered)\\nFalse Bay Braai (30\\/07\\/2022 03:00 to 30\\/07\\/2022 10:00) (Basic)\\nGold Restaurant (02\\/08\\/2022 12:00 to 02\\/08\\/2022 05:00) (Basic)\\nSoweto Tour (06\\/08\\/2022 11:00 to 06\\/08\\/2022 06:00) (Basic)\\nVineyards Tour (02\\/08\\/2022 11:00 to 02\\/08\\/2022 03:00) (Basic)\\nRyanAir (JNBRY0201) London Heathrow Airport to O.R. Tambo International Airport (28\\/07\\/2022 09:00 to 28\\/07\\/2022 17:00) (Economy)\\nRyanAir (LHRRY09221) O.R. Tambo International Airport to London Heathrow Airport (08\\/08\\/2022 09:00 to 08\\/08\\/2022 19:00) (Economy)\\nZAF Local (ZAFCPT010123) O.R. Tambo International Airport to Cape Town International Airport (04\\/08\\/2022 09:00 to 29\\/07\\/2022 12:00) (Economy)\\nTrain to JBR Airport from CPT Airport (Cape Town International Airport to O.R. Tambo International Airport) (Train)  (04\\/08\\/2022 14:00 to 04\\/08\\/2022 18:00) (Economy)\\n","cost":3498}]}}',
                'adjustments' => '{"total_cost":0,"billables":[]}',
                'payments' => '{"total_cost":0,"billables":[{"date":"2022-01-10T00:00:00.000000Z","description":"Stripe: Deposit","cost":300},{"date":"2022-01-12T10:00:00.000000Z","description":"Stripe: Refund","cost":-300}]}',
                'footer' => 'This is a demo tour, and will not be fulfilled',
                'total_cost' => 3498.0,
                'created_at' => '2022-01-23 13:48:42',
                'updated_at' => '2022-01-23 13:48:42',
                'deleted_at' => NULL,
                'notes' => NULL,
                'installments' => '[{"due":"With Order","amount":300,"paid":false},{"due":"2021-08-01","amount":692,"paid":false},{"due":"2021-11-01","amount":692,"paid":false},{"due":"2022-02-01","amount":692,"paid":false},{"due":"2022-05-10","amount":1084,"paid":false}]',
            ),
        ));
        
        
    }
}
