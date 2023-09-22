<?php

namespace Database\Seeders;

use App\Models\Order\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('orders')->delete();
        
        \DB::table('orders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100010001XGON',
                'deposit' => '300.00',
                'ordered_on' => '2022-01-23 00:00:00',
                'cancelled' => 1,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100020002KQRV',
                'deposit' => '300.00',
                'ordered_on' => '2022-01-04 10:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100030003PRSE',
                'deposit' => '300.00',
                'ordered_on' => '2022-01-23 00:00:00',
                'cancelled' => 1,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100040005XTJD',
                'deposit' => '300.00',
                'ordered_on' => '2022-01-21 00:00:00',
                'cancelled' => 1,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100050006PIRW',
                'deposit' => '300.00',
                'ordered_on' => '2022-05-31 14:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'created_at' => '2022-06-29 07:59:19',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100060009HBRY',
                'deposit' => '300.00',
                'ordered_on' => '2022-02-14 10:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 2,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000200070014IUMT',
                'deposit' => '300.00',
                'ordered_on' => '2022-09-14 11:22:23',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => '<p>This is a demo tour, and will not be fulfilled</p>',
                'created_at' => '2022-09-14 11:22:23',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => 'XOiHwpgVMqahAFnBK2HD9fxJ4o5P8bQImwD6ekzyBG9vfIEZ30jsnW3CSlk0eguL',
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 4,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000400080016UAHR',
                'deposit' => '300.00',
                'ordered_on' => '2022-09-20 10:39:17',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => '<p>The Tour Company will not be held responsible for any cancellation of this tour deemed to be resulting from an act of god</p>',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 5,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000500090019EBJK',
                'deposit' => '350.00',
                'ordered_on' => '2022-09-20 19:33:03',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => '<p>The Gambia is commonly known as the &quot;smiling coast&quot; of Africa. It is recognised for its beautiful white sandy beaches and for being home to Jufureh. The reputed ancestral village of Kunte Kinte, the main legend in Alex Haley well known novel &quot;Roots&quot;. This small West African country, surrounded by Senegal and the narrow Atlantic coastline. &nbsp;The diverse ecosystems is around the central Gambia River. The abundance of wildlife in its Kiang West National Park and Bao Bolong Wetland Reserve includes monkeys, leopards, hippos, hyenas and rare birds. The capital, Banjul, and nearby Serrekunda offer access to beaches.</p>',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => '0d8ONlQWIprehRXImuljW2jp1X6xAEYfVotQBSmtowrRD1EcM0CYqwc68g7A5FMy',
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 6,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000600100021YKXQ',
                'deposit' => '50.00',
                'ordered_on' => '2022-09-01 03:15:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => NULL,
                'created_at' => '2022-09-21 09:24:11',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 6,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000600110022HMFS',
                'deposit' => '50.00',
                'ordered_on' => '2022-08-13 13:30:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => NULL,
                'created_at' => '2022-09-21 09:27:53',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 6,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000600120023TQHV',
                'deposit' => '50.00',
                'ordered_on' => '2022-09-21 10:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => NULL,
                'created_at' => '2022-09-21 09:30:33',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'tour_id' => 6,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000600130024VFQL',
                'deposit' => '50.00',
                'ordered_on' => '2022-09-21 10:26:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => NULL,
                'created_at' => '2022-09-21 09:31:56',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'tour_id' => 5,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000500140026XNHU',
                'deposit' => '350.00',
                'ordered_on' => '2022-09-21 11:45:17',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => '<p>The Gambia is commonly known as the &quot;smiling coast&quot; of Africa. It is recognised for its beautiful white sandy beaches and for being home to Jufureh. The reputed ancestral village of Kunte Kinte, the main legend in Alex Haley well known novel &quot;Roots&quot;. This small West African country, surrounded by Senegal and the narrow Atlantic coastline. &nbsp;The diverse ecosystems is around the central Gambia River. The abundance of wildlife in its Kiang West National Park and Bao Bolong Wetland Reserve includes monkeys, leopards, hippos, hyenas and rare birds. The capital, Banjul, and nearby Serrekunda offer access to beaches.</p>',
                'created_at' => '2022-09-21 11:45:17',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => 'zjgZyBQvhlcM74W9m6P2Jwb2RtKxuRWbErkiXfVxPBGJ5zvS8tAG1pYD5KLcFOQ4',
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'tour_id' => 5,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000500150027NBRV',
                'deposit' => '350.00',
                'ordered_on' => '2022-09-21 13:30:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => '<p>The Gambia is commonly known as the &quot;smiling coast&quot; of Africa. It is recognised for its beautiful white sandy beaches and for being home to Jufureh. The reputed ancestral village of Kunte Kinte, the main legend in Alex Haley well known novel &quot;Roots&quot;. This small West African country, surrounded by Senegal and the narrow Atlantic coastline. &nbsp;The diverse ecosystems is around the central Gambia River. The abundance of wildlife in its Kiang West National Park and Bao Bolong Wetland Reserve includes monkeys, leopards, hippos, hyenas and rare birds. The capital, Banjul, and nearby Serrekunda offer access to beaches.</p>',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2023-09-21 13:09:39',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'tour_id' => 7,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000700160028LTMP',
                'deposit' => '300.00',
                'ordered_on' => '2023-09-22 14:20:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'invoice_footer' => '<p>This is a demo tour, and will not be fulfilled</p>',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
                'token' => NULL,
                'booking_fee' => '0.00',
                'organization_id' => NULL,
            ),
        ));
        
        $this->call(OrderCustomersTableSeeder::class);
        $this->updateLeadBooker(1, 1);
        $this->updateLeadBooker(2, 2);
        $this->updateLeadBooker(3, 3);
        $this->updateLeadBooker(4, 5);
        $this->updateLeadBooker(5, 6);
        $this->updateLeadBooker(6, 9);
        $this->updateLeadBooker(7, 14);
        $this->updateLeadBooker(8, 16);
        $this->updateLeadBooker(9, 19);
        $this->updateLeadBooker(10, 21);
        $this->updateLeadBooker(11, 22);
        $this->updateLeadBooker(12, 23);
        $this->updateLeadBooker(13, 24);
        $this->updateLeadBooker(14, 26);
        $this->updateLeadBooker(15, 27);
        $this->updateLeadBooker(16, 28);
    }

    private function updateLeadBooker(int $orderId, int $leadBookerId)
    {
        $order = Order::find($orderId);
        $order->lead_booker_id = $leadBookerId;
        $order->save();
    }
}