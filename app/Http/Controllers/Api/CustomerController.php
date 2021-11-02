<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderCustomer;

class CustomerController extends ApiController
{
    protected $logging = false;
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

        $orders = new Order();
        $order = $orders->where('token', $token)->first();
        if (isset($order)) {
            if ($this->logging) Log::info('orders are ', $order->toArray());
            $orderCustomer = new OrderCustomer();
            $orderCustomers = $orderCustomer
                ->select('customers.*', 'order_customers.*', 'order_customers.id as order_customer_id')
                ->join('customers', 'order_customers.customer_id', 'customers.id')
                ->where('order_id', $order->id)
                ->whereNull('order_customers.deleted_at')
                // order by isLead desc so lead is first
                ->orderBy('order_customers.is_lead_booker', 'desc')
                ->get();
            if (count($orderCustomers)) {
                $order->customer = $orderCustomers[0];
                $order->customers = $orderCustomers;
                $this->logging && Log::info('order data for customer retrieved ', $orderCustomers->toArray());
                return response()->json(['success' => true, 'orders' => $order]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            $this->logging && Log::info('no order found for token: '. $token);
        }
        return null;
    }

    public function getCustomerOrdersByEmail($email)
    {
        $customerOrders = OrderCustomer::select('orders.token', 'order_customers.order_id')
            ->join('customers', 'order_customers.customer_id', 'customers.id')
            ->join('orders', 'order_customers.order_id', 'orders.id')
            ->where('customers.email_address', $email)
            ->whereNull('order_customers.deleted_at')
            ->get();
        // Log::info('getOrderByEmail: ', $customerOrders->toArray());
        return response()->json(['success' => true,
            'data' => $customerOrders]);
            // select * from `order_customers`
            // inner join `customers` on `order_customers`.`customer_id` = `customers`.`id`
            // inner join `orders` on `order_customers`.`order_id` = `orders`.`id`
            // where `order_customer.customer_id` = ? and `customer`.`email_address` = ?
            // deleted_at `order_customers` is null";
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
            ->select('orders.id as order_id', 'order_customers.id as order_customer_id', 'order_customers.is_lead_booker', 'customers.id as customer_id', 'customers.first_name', 'customers.last_name')
            ->join('order_customers', 'order_customers.customer_id', 'customers.id')
            ->join('orders', 'orders.id', 'order_customers.order_id')
            ->where('orders.id', $request->order_id)->get();
        
            return $customers->toJson();
    }
}
