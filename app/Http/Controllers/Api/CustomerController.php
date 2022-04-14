<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Booking\Booking;
use App\Models\Customer\Customer;
use App\Repository\AddressRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends ApiController
{
    protected $logging = false;

    /**
     * getCustomerByToken
     * 
     * @param $token
     * @return JSON $customer or NULL if token no longer valid
     */
    public function getCustomerByToken($token = null)
    {
        if (empty($token)) {
            Log::info('getCustomerOrderByToken: no token');
            return null;
        }

        $home_address = null;
        $billing_address = null;
        $booking = Booking::where('token', $token)->first();
        if (empty($booking)) {
            Log::warning('CustomerController::getCustomerByToken: WARNING: no booking for token '.$token);
            return null;
        }

        $customer = Customer::find($booking->customer_id);
        if (empty($customer)) {
            Log::warning('CustomerController::getCustomerByToken: WARNING: no customer for token '.$token);
            return null;
        }

        $addressRepo = new AddressRepository();
        if (isset($customer->home_address_id)) {
            $customer->home_address = $addressRepo->get($customer->home_address_id);
        }
        if (isset($customer->billing_address_id)) {
            $customer->billing_address = $addressRepo->get($customer->billing_address_id);
        }

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    /**
     * getTokenLink: check is deprecated
     *
     * @param Request $request
     * @return JSON
     */
    public function getTokenLink(Request $request)
    {
        Log::warning('CustomerController::getTokenLink call should be deprecated');

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

    /**
     * findCustomerByEmail
     *
     * @param Request $request
     * @return JSON Customer
     */
    public function findCustomerByEmail(Request $request) {
        $email_address = $request->email_address;
        $customers = new Customer();
        $customer = $customers->where('email_address', $email_address)->get();
        if(!$customer->count()) {
            return response()->json(['success' => false]);
        }
        if ($customer->count() > 1) {
            Log::warning('CustomerController API::findCustomerByEmail found more than one email address for '.$email_address);
        }

        return response()->json(['success' => true, 'customer' => $customer[0]]);
    }
}
