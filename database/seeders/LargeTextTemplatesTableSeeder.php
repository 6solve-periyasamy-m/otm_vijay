<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LargeTextTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('large_text_templates')->delete();
        
        \DB::table('large_text_templates')->insert(array (
            0 => 
            array (
                'id' => 3,
                'name' => 'OTM T&Cs',
                'type' => 1,
                'description' => NULL,
            'content' => '<p>When you buy an ATOL protected flight, or flight inclusive holiday from us you will receive an ATOL Certificate within 24 hours. This lists what is financially protected, where to find information on what this means for you and who to contact in the unlikely event that we cease to trade.</p><p>All airline suppliers provided will be listed on the EU Air Safety List. It is our commitment to you, to ensure all providers adhere to the highest safety standards set by relevant governing bodies including ABTA and the CAA.</p><p>The combination of travel services offered to you is a package within the meaning of the Package Travel and Linked Travel Arrangements Regulations. Therefore, you will benefit from all EU rights applying to packages. We, OTM will be fully responsible for the proper performance of the package as a whole. Additionally, as required by law, we OTM Ltd has protection in place to refund your payments and, where transport is included in the package, to ensure your repatriation in the event that it becomes insolvent.</p><p><strong>Your Payment Schedule</strong></p><p>Your payment schedule is shown on your Activity Statement that will accompany your Tour Confirmation. Your payments are schedules ad above</p><p>Failure to adhere to this schedule can result in the cancellation of your tour and charges as per clause 5 in our Terms &amp; Conditions. You will be responsible for ensuring all payments for your passengers are paid by the dates specified above. If you would like them to pay separately, please let us know as soon as possible.</p><p><strong>Your tour exclusions</strong></p><p>The following items are not including in the cost of your tour. If you wish to have a quote for any of the following (excluding match tickets), please do let us know.</p><ul><li>Meals unless specified</li><li>Insurance</li><li>UK airport transfers</li><li>Visa fees</li><li>Inoculations</li></ul><p><strong>Visas, passports and health</strong></p><p>It is your responsibility to ensure that all passengers, including foreign nationals, satisfy the appropriate visa requirements of countries to be visited or passed through on tour.</p><p><strong>Country of visit</strong>:&nbsp; South Africa</p><p><strong>FCO Website</strong>:&nbsp;<a href="https://www.gov.uk/foreign-travel-advice/south-africa">https://www.gov.uk/foreign-travel-advice/south-africa</a></p><ul><li>Please check the FCO page for passport validity requirements</li><li>You may need a Visa. Please refer to&nbsp;<a href="https://www.gov.uk/foreign-travel-advice/south-africa/entry-requirements">https://www.gov.uk/foreign-travel-advice/south-africa/entry-requirements</a></li><li>Please visit your health professional at least 12 weeks before your trip to check whether you&nbsp;need any vaccinations or other preventative measures. Health requirements can be found here&nbsp;<a href="https://www.gov.uk/foreign-travel-advice/south-africa/health">https://www.gov.uk/foreign-travel-advice/south-africa/health</a></li></ul>',
                'created_at' => '2024-06-03 10:05:53',
                'updated_at' => '2024-06-06 14:59:27',
                'default' => 1,
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'OTM Footer',
                'type' => 2,
                'description' => 'Default OTM Footer ',
                'content' => '<p>To pay us, leave a white envelope under the tube station seat.</p>',
                'created_at' => '2024-06-06 14:59:58',
                'updated_at' => '2024-06-06 14:59:58',
                'default' => 1,
            ),
        ));
        
        
    }
}