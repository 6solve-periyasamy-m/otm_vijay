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
                'base_price_per_person' => '3498.00',
                'margin' => '0.00',
                'single_occupancy_surcharge' => '419.67',
                'deposit' => '300.00',
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'double-test-impala-pride',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 0,
                'date_from' => '2022-07-28',
                'date_to' => '2022-08-09',
                'invoice_footer' => '<p>This is a demo tour, and will not be fulfilled</p>',
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
                'final_payment' => '2022-06-30',
                'created_at' => '2022-01-20 11:32:05',
                'updated_at' => '2022-09-14 09:39:10',
                'deleted_at' => NULL,
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '0.00',
                'brand_id' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'event_id' => 1,
                'name' => 'The Impala 2023 - England v SA - Double Test',
            'description' => 'The Impala (Tests 2 & 3)',
                'notes' => NULL,
                'base_price_per_person' => '3498.00',
                'margin' => '0.00',
                'single_occupancy_surcharge' => '419.67',
                'deposit' => '300.00',
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'double-test-impala-2023',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2023-07-28',
                'date_to' => '2023-08-02',
                'invoice_footer' => '<p>The Tour Company will not be held responsible for any cancellation of this tour deemed to be resulting from an act of god</p>',
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
                'final_payment' => '2023-06-30',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 13:46:05',
                'deleted_at' => NULL,
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '0.00',
                'brand_id' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'event_id' => 3,
                'name' => 'Ghana Tour',
                'description' => 'Cultural experience throughout Accra',
                'notes' => NULL,
                'base_price_per_person' => '1800.00',
                'margin' => '10.00',
                'single_occupancy_surcharge' => '299.00',
                'deposit' => '399.00',
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'ghana-tour',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2023-10-31',
                'date_to' => '2023-11-14',
                'invoice_footer' => '<p>This is test information for the invoice Footer.</p>

<p>AGA Tours will not be responsible in the event of an emergency</p>',
                'terms' => '<p>This is test information for the Terms.</p>

<p>AGA Tours will not be responsible in the event of an emergency</p>',
                'final_payment' => '2023-09-30',
                'created_at' => '2022-09-14 12:07:31',
                'updated_at' => '2022-09-14 14:46:12',
                'deleted_at' => '2022-09-14 14:46:12',
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '0.00',
                'brand_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'event_id' => NULL,
                'name' => 'The Impala 2023 - England v SA - Double Test',
            'description' => 'The Impala (Tests 2 & 3)',
                'notes' => NULL,
                'base_price_per_person' => '0.00',
                'margin' => NULL,
                'single_occupancy_surcharge' => '0.00',
                'deposit' => NULL,
                'stock_control_active' => 0,
                'stock' => NULL,
                'booking_form_url' => NULL,
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 0,
                'date_from' => '2023-07-28',
                'date_to' => '2023-07-28',
                'invoice_footer' => NULL,
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
                'final_payment' => '2023-06-30',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '0.00',
                'brand_id' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'event_id' => NULL,
                'name' => 'The Gambia Experience',
                'description' => NULL,
                'notes' => NULL,
                'base_price_per_person' => '1199.00',
                'margin' => '250.00',
                'single_occupancy_surcharge' => '295.00',
                'deposit' => '350.00',
                'stock_control_active' => 1,
                'stock' => 20,
                'booking_form_url' => 'thegambiaexperience',
                'tour_category_id' => 1,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2022-11-18',
                'date_to' => '2022-11-29',
                'invoice_footer' => '<p>The Gambia is commonly known as the &quot;smiling coast&quot; of Africa. It is recognised for its beautiful white sandy beaches and for being home to Jufureh. The reputed ancestral village of Kunte Kinte, the main legend in Alex Haley well known novel &quot;Roots&quot;. This small West African country, surrounded by Senegal and the narrow Atlantic coastline. &nbsp;The diverse ecosystems is around the central Gambia River. The abundance of wildlife in its Kiang West National Park and Bao Bolong Wetland Reserve includes monkeys, leopards, hippos, hyenas and rare birds. The capital, Banjul, and nearby Serrekunda offer access to beaches.</p>',
                'terms' => '<p>Client Advice: Allocated seat numbers may be subject to change to accommodate family groups or if single empty seats are created; Please be aware the airline operates a cash free policy on board and there is no duty free available to purchase; Each infant has a 10kg luggage allowance; Please ensure you are aware of all entry requirements relating to Covid-19 - https://www.gov.uk/foreign-travel-advice/the-gambia/entry-requirements</p>

<p>&nbsp;</p>',
                'final_payment' => '2022-10-07',
                'created_at' => '2022-09-20 18:40:21',
                'updated_at' => '2022-09-20 19:49:15',
                'deleted_at' => NULL,
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '0.00',
                'brand_id' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'event_id' => NULL,
                'name' => 'Royal Ascot African Ladies 2023',
                'description' => 'Snacks, Canapes & Drinks on the coach. Main Course Meal & Desserts, Entertainment & Lots of Love & Joy!',
                'notes' => NULL,
                'base_price_per_person' => '150.00',
                'margin' => '0.00',
                'single_occupancy_surcharge' => '0.00',
                'deposit' => '50.00',
                'stock_control_active' => 1,
                'stock' => 15,
                'booking_form_url' => 'ascot-tour-2023',
                'tour_category_id' => 1,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2023-06-20',
                'date_to' => '2023-06-24',
                'invoice_footer' => NULL,
                'terms' => '<p>This is a demo tour and will not be fulfilled</p>',
                'final_payment' => '2023-04-30',
                'created_at' => '2022-09-21 08:43:50',
                'updated_at' => '2022-09-21 11:48:06',
                'deleted_at' => NULL,
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '0.00',
                'brand_id' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'event_id' => 1,
                'name' => 'Double Test - The Impala - 2024',
            'description' => 'Double Test - The Impala (Tests 2 & 3)',
                'notes' => NULL,
                'base_price_per_person' => '3498.00',
                'margin' => '0.00',
                'single_occupancy_surcharge' => '420.00',
                'deposit' => '300.00',
                'stock_control_active' => 0,
                'stock' => 50,
                'booking_form_url' => 'double-test-impala-pride-2',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2024-07-28',
                'date_to' => '2024-08-09',
                'invoice_footer' => '<p>This is a demo tour, and will not be fulfilled</p>',
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
                'final_payment' => '2024-06-30',
                'created_at' => '2023-09-21 13:23:19',
                'updated_at' => '2023-09-21 13:23:19',
                'deleted_at' => NULL,
                'accommodation_stock_control' => 0,
                'activity_stock_control' => 1,
                'flight_stock_control' => 0,
                'transport_stock_control' => 0,
                'merchandise_stock_control' => 0,
                'atol_protected' => NULL,
                'booking_fee' => '5.00',
                'brand_id' => NULL,
            ),
        ));
        
        
    }
}