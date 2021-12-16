<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_schedules')->delete();
        DB::table('payment_schedules')->insert([
            0 => [
                'name' => 'Default',
                'deposit_type' => 'percentage',
                'deposit' => '50',
                'installment_period' => 'monthly',
                'installment_type' => 'fixed',
                'installment' => '500',
            ],
            1 => [
                'name' => 'Full Payment',
                'deposit_type' => 'percentage',
                'deposit' => '100',
                'installment_period' => 'monthly',
                'installment_type' => 'fixed',
                'installment' => '250',
            ],
            2 => [
                'name' => 'Fast Track',
                'deposit_type' => 'percentage',
                'deposit' => '60',
                'installment_period' => 'monthly',
                'installment_type' => 'fixed',
                'installment' => '1000',
            ],
            3 => [
                'name' => 'Extended',
                'deposit_type' => 'percentage',
                'deposit' => '10',
                'installment_period' => 'monthly',
                'installment_type' => 'fixed',
                'installment' => '250',
            ],
            4 => [
                'name' => 'Weekly',
                'deposit_type' => 'percentage',
                'deposit' => '10',
                'installment_period' => 'weekly',
                'installment_type' => 'fixed',
                'installment' => '50',
            ],
            5 => [
                'name' => 'Long term',
                'deposit_type' => 'amount',
                'deposit' => '100',
                'installment_period' => 'monthly',
                'installment_type' => 'percentage',
                'installment' => '5',
            ]
        ])
    }
}
