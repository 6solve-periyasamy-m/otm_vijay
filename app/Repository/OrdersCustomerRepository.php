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
}
