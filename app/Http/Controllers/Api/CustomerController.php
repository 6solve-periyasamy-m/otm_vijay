<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cookie;

use App\Models\Order;
use App\Models\Customer;

use App\Repository\AddressRepository;
use App\Models\OrderCustomer;

class CustomerController extends ApiController
{
    protected $logging = false;
    /** 
     * getCustomerByToken - No longer used by booking form, the user can find their bookings by token
     * or authenticate to access their bookings by customer_id: may be adapted to getCustomerByEmail
     * as a secure way to restore booking tokens before logging in
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

        $home_address = null;
        $billing_address = null;
        $customers = new Customer();
        $customer = $customers->where('login_token', $token)->first();
        if (empty($customer)) {
            Log::warning('CustomerController::getCustomerByToken: WARNING: no customer for token '.$token);
            return null;
        }

        $addressRepo = new AddressRepository();
        if (isset($customer->home_address_id)) {
            $home_address = $addressRepo->get($customer->home_address_id);
        }
        if (isset($customer->billing_address_id)) {
            $billing_address = $addressRepo->get($customer->billing_address_id);
        }

        return response()->json(['success' => true, 'customer' => $customer, 'home_address' => $home_address, 'billing_address' => $billing_address]);
    }

    /**
     * may be adapted to return booking tokens from email?
     *
     * @param Request $request
     * @return void
     */
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

    // /**
    //  * getTravellers for this order: could be adapted to use the bookings
    //  * Route: /api/booking/tourparty
    //  * @param Request $request
    //  * @return JSON
    //  */
    // public function getTravellers(Request $request) {
    //     if (empty($request->order_id)) {
    //         Log::error('CustomerController::getTravellers: ERROR: requires an order_id');
    //         return null;
    //     }
    //     $customer = new Customer();
    //     $customers = $customer
    //         ->select('orders.id as order_id', 'order_customers.id as order_customer_id', 'order_customers.is_lead_booker', 'customers.id as customer_id', 'customers.first_name', 'customers.last_name')
    //         ->join('order_customers', 'order_customers.customer_id', 'customers.id')
    //         ->join('orders', 'orders.id', 'order_customers.order_id')
    //         ->where('orders.id', $request->order_id)->get();
        
    //         return $customers->toJson();
    // }

    public function findCustomerByEmail(Request $request) {
        $email_address = $request->email_address;
        $customers = new Customer();
        $customer = $customers->where('email_address', $email_address)->get();
        if(!$customer->count()) {
            return response()->json(['success' => false]);
        }

        return response()->json(['success' => true, 'customer' => $customer[0]]);
    }
}
