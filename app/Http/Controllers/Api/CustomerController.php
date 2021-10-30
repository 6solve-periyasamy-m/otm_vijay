<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cookie;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrdersCustomer;

class CustomerController extends ApiController
{
    protected $logging = false;
    /** 
     * getCustomerByToken
     * 
     * @param $token
     * @return $customer or NULL if token no longer valid
     */
    public function getCustomerByToken($token = null)
    {
        if (empty($token)) {
            Log::info('getCustomerOrderByToken: no token');
            return null;
        }

        $customers = new Customer();
        $customer = $customers->where('login_token', $token)->first();
        if (isset($customer)) {
            Log::info('getCustomerByToken', $customer->toArray());
        } else {
            Log::info('no data retrieved for token: '. $token);
        }

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function DEPRECATE_getCustomerOrderByToken($token = null)
    {
        if (empty($token)) {
            Log::info('getCustomerOrderByToken: no token');
            return null;
        }

        $customers = new Customer();
        $customer = $customers->where('login_token', $token)->first();


        $orders = new Order();
        $order = $orders->where('token', $token)->first();
        if (isset($order)) {
            if ($this->logging) Log::info('orders are ', $order->toArray());
            $ordersCustomer = new OrdersCustomer();
            $orderCustomers = $ordersCustomer
                ->select('customers.*', 'orders_customers.*', 'orders_customers.id as order_customer_id')
                ->join('customers', 'orders_customers.customer_id', 'customers.id')
                ->where('order_id', $order->id)
                ->whereNull('orders_customers.deleted_at')
                // order by isLead desc so lead is first
                ->orderBy('orders_customers.is_lead_booker', 'desc')
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

    public function getTokenLink(Request $request)
    {
        $email = $request->email;
        if (empty($email)) {
            return response()->json(['success' => false]);
        }

        $customer = Customer::where('email', $email)->first();
        if (empty($customer->login_token)) {
            $customer->login_token = sha1(time());
            $customer->save();
        }

        return response()->json(["success" => true])->cookie("login_token", $customer->login_token, 60);
    }

    public function getCustomerOrdersByEmail($email)
    {
        // DEPRECATE
        // $customerOrders = OrdersCustomer::select('orders.token', 'orders_customers.order_id')
        //     ->join('customers', 'orders_customers.customer_id', 'customers.id')
        //     ->join('orders', 'orders_customers.order_id', 'orders.id')
        //     ->where('customers.email_address', $email)
        //     ->whereNull('orders_customers.deleted_at')
        //     ->get();
        // // Log::info('getOrderByEmail: ', $customerOrders->toArray());
        // return response()->json(['success' => true,
        //     'data' => $customerOrders]);
        //     // select * from `orders_customers` 
        //     // inner join `customers` on `orders_customers`.`customer_id` = `customers`.`id` 
        //     // inner join `orders` on `orders_customers`.`order_id` = `orders`.`id` 
        //     // where `orders_customer.customer_id` = ? and `customer`.`email_address` = ? 
        //     // deleted_at `orders_customers` is null";
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
