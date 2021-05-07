<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrdersCustomer;

class CustomerController extends ApiController
{
    /** 
     * getCustomerByToken
     * 
     * @param $token
     * @return $customer or NULL if token no longer valid
     */
    public function getCustomerOrderByToken($token = null)
    {
        if (empty($token)) {
            Log::info('getCustomerOrderByToken: no token');
            return null;
        }

        $order = new Order();
        $orders = $order->where('token', $token)->get();
        if ($this->logging) {
            Log::info($token . ' found '. count($orders). ' orders');
        }

        if (count($orders)) {
            $orderCount = count($orders);
            if ($orderCount > 1) {
                Log::info('Multiple orders '.$orderCount.' for token '. $token);
            }

            if ($this->logging) Log::info('orders are ', $orders->toArray());
            
            foreach ($orders as &$ord) {
                $ordersCustomers = new OrdersCustomer();
                $orderCustomer = $ordersCustomers->where('order_id', $ord->id)
                    ->join('customers', 'orders_customers.customer_id', 'customers.id')
                    ->get();
                if (count($orderCustomer)) {
                    $ord->customer = $orderCustomer[0];
                    $ord->customers = $orderCustomer;
                }
            }
            if ($this->logging) Log::info('order data for customer retrieved ', $orders->toArray());

            return $orders;
        }
        return null;
    }

    /**
     * getTravellers for this order
     *
     * @param Request $request
     * @return JSON
     */
    public function getTravellers(Request $request) {
        if (empty($request->order_id)) {
            Log::debug('ERROR: getTravellers requires an order_id');
            return null;
        }
        $customer = new Customer();
        $customers = $customer
            ->select('orders.id as order_id', 'orders_customers.id as order_customer_id', 'orders_customers.is_lead_booker', 'customers.id as customer_id', 'customers.first_name', 'customers.last_name')
            ->join('orders_customers', 'orders_customers.customer_id', 'customers.id')
            ->join('orders', 'orders.id', 'orders_customers.order_id')
            ->where('orders.id', $request->order_id)->get();
        
            return $customers->toJson();
    }
}
