<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tours')->delete();
        
        \DB::table('tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'event_id' => 1,
                'name' => 'Double Test - The Impala',
            'description' => 'Double Test - The Impala (Tests 2 & 3)',
                'notes' => NULL,
                'base_price_per_person' => 3498.0,
                'margin' => 0.0,
                'single_occupancy_surcharge' => 419.67,
                'deposit' => 300.0,
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'double-test-impala-pride',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2022-07-28',
                'date_to' => '2022-08-09',
                'created_at' => '2022-01-20 11:32:05',
                'updated_at' => '2022-01-21 12:05:20',
                'deleted_at' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'final_payment' => '2022-06-30',
                'terms' => '<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt"><span style="color:black">When you buy an ATOL protected flight, or flight inclusive holiday from us you will receive an ATOL Certificate within 24 hours. This lists what is financially protected, where to find information on what this means for you and who to contact in the unlikely event that we cease to trade.</span></span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">All airline suppliers provided will be listed on the EU Air Safety List. It is our commitment to you, to ensure all providers adhere to the highest safety standards set by relevant governing bodies including ABTA and the CAA.</span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">The combination of travel services offered to you is a package within the meaning of the Package Travel and Linked Travel Arrangements Regulations. Therefore, you will benefit from all EU rights applying to packages. We, OTM will be fully responsible for the proper performance of the package as a whole. Additionally, as required by law, we OTM Ltd has protection in place to refund your payments and, where transport is included in the package, to ensure your repatriation in the event that it becomes insolvent.</span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><strong>Your Payment Schedule</strong></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">Your payment schedule is shown on your Activity Statement that will accompany your Tour Confirmation. Your payments are schedules ad above</span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">Failure to adhere to this schedule can result in the cancellation of your tour and charges as per clause 5 in our Terms &amp; Conditions. You will be responsible for ensuring all payments for your passengers are paid by the dates specified above. If you would like them to pay separately, please let us know as soon as possible.</span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><strong>Your tour exclusions</strong></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">The following items are not including in the cost of your tour. If you wish to have a quote for any of the following (excluding match tickets), please do let us know.</span></span></p>

<ul style="list-style-type:disc">
	<li><span style="font-size:12pt"><span style="font-size:10pt">Meals unless specified</span></span></li>
	<li><span style="font-size:12pt"><span style="font-size:10pt">Insurance</span></span></li>
	<li><span style="font-size:12pt"><span style="font-size:10pt">UK airport transfers</span></span></li>
	<li><span style="font-size:12pt"><span style="font-size:10pt">Visa fees</span></span></li>
	<li><span style="font-size:12pt"><span style="font-size:10pt">Inoculations</span></span></li>
</ul>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><strong>Visas, passports and health</strong></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">It is your responsibility to ensure that all passengers, including foreign nationals, satisfy the appropriate visa requirements of countries to be visited or passed through on tour.</span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><strong><span style="font-size:10pt">Country of visit</span></strong><span style="font-size:10pt">:&nbsp; South Africa</span></span></p>

<p style="margin-left:0; margin-right:0; text-align:start"><span style="font-size:12pt"><strong><span style="font-size:10pt">FCO Website</span></strong><span style="font-size:10pt">:&nbsp;</span><a href="https://www.gov.uk/foreign-travel-advice/south-africa" rel="noopener noreferrer" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; background-color: transparent; outline-style: none; color: rgb(147, 153, 245); text-decoration: none;" tabindex="-1" target="_blank" title="https://www.gov.uk/foreign-travel-advice/south-africa">https://www.gov.uk/foreign-travel-advice/south-africa</a> </span></p>

<ul>
	<li style="text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">Please check the FCO page for passport validity requirements</span></span></li>
	<li style="text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">You may need a Visa. Please refer to&nbsp;</span><a href="https://www.gov.uk/foreign-travel-advice/south-africa/entry-requirements" rel="noopener noreferrer" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; background-color: transparent; outline-style: none; color: rgb(147, 153, 245); text-decoration: none;" tabindex="-1" target="_blank" title="https://www.gov.uk/foreign-travel-advice/south-africa/entry-requirements">https://www.gov.uk/foreign-travel-advice/south-africa/entry-requirements</a><span style="font-size:10pt"> </span></span></li>
	<li style="text-align:start"><span style="font-size:12pt"><span style="font-size:10pt">Please visit your health professional at least 12 weeks before your trip to check whether you&nbsp;need any vaccinations or other preventative measures. Health requirements can be found here&nbsp;</span><a href="https://www.gov.uk/foreign-travel-advice/south-africa/health" rel="noopener noreferrer" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; background-color: transparent; outline-style: none; color: rgb(147, 153, 245); text-decoration: none;" tabindex="-1" target="_blank" title="https://www.gov.uk/foreign-travel-advice/south-africa/health">https://www.gov.uk/foreign-travel-advice/south-africa/health</a></span></li>
</ul>',
            ),
        ));
        
        
    }
}
