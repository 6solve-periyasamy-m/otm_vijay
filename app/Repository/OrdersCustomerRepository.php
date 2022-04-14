<?php

namespace App\Repository;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\OrdersCustomer;

interface OrdersCustomerRepositoryInterface {
}

class OrdersCustomerRepository implements OrdersCustomerRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new OrdersCustomer();
    }

    public function getCustomerOrder($order_id, $customer_id)
    {
        // get the customer order record
        $customerOrder = new OrdersCustomer();
        Log::info('getting customerOrder', [$order_id, $customer_id]);
        try {
            $customerOrders = $customerOrder
                ->where('order_id', $order_id)
                ->where('id', $customer_id)
                ->get();
        } catch (Exception $e) {
            Log::info('error' . $e->getMessage());
            die('fail');
        }

        // if ($customerOrders->count() > 1) {
        //     Log::info('WARNING: postAccommodationReservation found more than one record for customer '.$traveller->customer_id.' order '.$order_id);
        // }

        if ($customerOrders->count() === 1) {
            $customerOrder = $customerOrders[0];
        } else {
            Log::info('wtf? '.  $order_id . ', c='. $customer_id);
        }

        return $customerOrder;
    }

    /**
     * updateOrderCustomer - adds fields to existing orderCustomer record for a single traveller
     *
     * @param [type] $ordersCustomer (object)
     * @param Request $request
     * @return void
     */
    private function updateOrderCustomerFields($ordersCustomer, $request)
    {
        if (!empty($request->tour['base_price_per_person'])) {
            $ordersCustomer->tour_cost = $request->tour['base_price_per_person'];
        }
        if (!empty($request->tour['single_occupancy_surcharge'])) {
            $ordersCustomer->single_occupancy_surcharge = $request->tour['single_occupancy_surcharge'];
        }
    }

    public function storeOrderCustomer($customer, $request, $isLead)
    {
        $ordersCustomer = new OrdersCustomer();
        $this->logging == 'orders' && Log::info('loading ordercustomer  order '. $request->order_id.' customer: '.$customer->id);
        $ordersCustomerExists = $ordersCustomer
            ->where('order_id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->first();
        if ($ordersCustomerExists) {
            $ordersCustomer = $ordersCustomerExists;
            $this->logging == 'orders' && Log::info('orderCustomer record', $ordersCustomerExists->toArray());
            $this->updateOrderCustomerFields($ordersCustomer, $request);
        // Log::info('ordercustomer exists, updating');
        } else {
            $ordersCustomer->order_id = $request->order_id;
            $ordersCustomer->customer_id = $customer->id;
            $this->logging == 'orders' && Log::info('creating ordercustomer for order '. $request->order_id.' customer: '.$customer->id);
        }
        $ordersCustomer->is_lead_booker = $isLead;
        $ordersCustomer->travel_insurer = null;
        $ordersCustomer->policy_number = null;
        $ordersCustomer->save();
    }
}
