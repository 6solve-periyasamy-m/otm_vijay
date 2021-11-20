<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;

use App\Models\Customer;
use Illuminate\Http\Request;

use App\Models\OrderCustomer;
use App\Models\AdditionalTraveller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repository\AddressRepository;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;
use App\Http\Controllers\ApiController;
use App\Repository\OrdersCustomerRepository;
use App\Repository\AdditionalCustomerRepository;
use App\Repository\AdditionalTravellerRepository;

class BookingCustomerController extends ApiController
{
    protected $logging = 'customers';
    private $authRequests = [];

    /**
     * checkActiveUser: is the email address registered already?
     *
     * @param String $email
     * @return Boolean $isRegistered
     */
    public function checkActiveUser($email)
    {
        $customerRepo = new CustomerRepository();
        $isRegistered = $customerRepo->isRegistered($email);

        return response()->json(["success" => true, "existing" => $isRegistered]);
    }

    /**
     * returns a random salt hash for use with an email
     *
     * @param [type] $email
     * @return void
     */
    public function salt($email)
    {
        $t = intval(time() % 65535);
        $raw = sprintf('%s%d', $email, $t);
        $auth = Hash::make($raw);
        $this->authRequests[$email] = $auth;

        return response()->json(["success" => true, "auth" => $auth]);
    } 

    // interesting idea, but replace with regular user login
    // by adding a callback URL to it.
    public function authenticate(Request $request)
    {
        // ignore auth label
        $token = substr($request->header('Authorization'), 6);
        $decoded = base64_decode($token);
        $decoded = base64_decode($decoded);
        $parts = explode(':', $decoded);

        $email = $parts[2];
        $password = $parts[1];
        $isalt = $parts[0];
        Log::info('active authRequests', $this->authRequests);
        $salt = $this->authRequests[$email]; 
        unset($this->authRequests[$email]);

        if ($isalt !== $salt) {
            return response()->json(["success" => false]);
        }

        $authorized = false;
        $customer = new Customer();
        $customer->where('email_address', $email)
            ->where('password', Hash::make($password))
            ->first();
        $authorized = $customer->count() === 1;

        return response()->json(["success" => true, "authorized" => $authorized]);
    }
    /**
     * storeOrUpdateOrderCustomer - stores the orderCustomer data from the booking form
     *
     * @param Customer $customer
     * @param Request $request
     * @param boolean $isLead
     * @return JSON (record saved)
     */
    private function storeOrUpdateOrderCustomer($customer, Request $request, $isLead = false)
    {
        if (empty($request->order_id)) {
            throw new \Exception('storeOrUpdateOrderCustomer has no order ID');
        }

        $ordersCustomerRepository = new OrdersCustomerRepository;
        $ordersCustomer = $ordersCustomerRepository->storeOrderCustomer($customer, $request, $isLead);

        return $ordersCustomer;
    }

    /**
     * storeOrUpdateCustomer
     * NB: customer table as a password field, this is not changed or affected by this function
     * as it is not used UNTIL the customer has a login and then the customer is authenticated
     *
     * @param [type] $request
     * @param $token
     * @param boolean $isLead
     * @return JSON (Customer object)
     */
    private function storeOrUpdateCustomer($request, $token, $isLead = false)
    {
        $this->logging == 'customers' && Log::info('search for '. $request->email_address);
        // Log::debug('check booking token', [$request->booking_token]);

        // validation
        if ($isLead) {
            $validated = $request->validate([
                'email_address' => 'required | email:rfc,dns',
                'title' => 'required',
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha_dash',
                'date_of_birth' => 'required | before: 18 years ago',
                'mobile_number' => 'required',
                'gender' => 'required',
                'address_line_1' => 'required',
                'address_line_2' => 'required',
                'town' => 'required',
                'country' => 'required',
                'postcode' => 'required'
            ]);
            if (!$request->same_address) {
                Log::info('validation of billing address', [$request->billing_address_line_1]);
                $billingValidated = $request->validate([
                    'billing_town' => 'required',
                    'billing_country' => 'required',
                    'billing_postcode' => 'required',
                    'billing_address_line_1' => 'required'
                ]);
                if ($this->logging == 'customers') {
                    Log::info('Billing Address Customer Validation passed', $billingValidated);
                }
            }
        } else {
            $validated = $request->validate([
                'email_address' => 'required | email:rfc,dns',
                'title' => 'required',
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha_dash',
                'date_of_birth' => 'required | before: 18 years ago',
                'mobile_number' => 'required',
                'gender' => 'required'
            ]);
            Log::info('validation passed');
        }
        if ($this->logging == 'customers') {
            Log::info('Basic Customer Validation passed', $validated);
        }

        $customerRepo = new CustomerRepository();
        $customerData = [
            'home_address_id' => isset($customer) ? $customer->home_address_id : 0,
            'billing_address_id' => isset($customer) ? $customer->billing_address_id : 0,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'title' => $request->title,
            'first_name' => $request->first_name,
            'middle_names' => $request->middle_names,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'mobile_number' => $request->mobile_number,
            'other_phone_number' => $request->other_phone_number,
            'gender' => $request->gender
        ];
        $customer = Customer::where('email_address', $request->email_address)->first();

        // if the customer exists, then the addresses MAY exist
        if ($customer) {
            $this->logging == 'customers' && Log::info('customer exists record ', $customer->toArray());
            // Log::debug('>>>>> update customer with ', $customerData);
            $customer = $customerRepo->update($customerData);
            
            // in booking form, only the lead enters addresses
            if ($isLead) {
                $addressIds = $this->update_addresses($request, $customer);
                // Log::debug('<<<<< update_address returned with ', $addressIds);
                $customerData['home_address_id'] = $addressIds['home_address_id'];
                $customerData['billing_address_id'] = $addressIds['billing_address_id'];
            }
            $customer = $customerRepo->update($customerData);
        } else {
            // $customer->email_address = $request->email_address;
            if ($isLead) {
                // a new lead customer record creates the booking record and address records
                $addressIds = $this->create_addresses($request);
                // Log::debug('<<<<< create_addressess returned with ', $addressIds);
                $customerData['home_address_id'] = $addressIds['home_address_id'];
                $customerData['billing_address_id'] = $addressIds['billing_address_id'];
            }
            $customer = $customerRepo->create($customerData);
        }

        if (!$isLead) {
            $bookingRepo = new BookingRepository();
            $booking = $bookingRepo->findBookingByToken($token);
            if ($booking) {
                $additionalTraveller = new AdditionalTravellerRepository();
                $newTraveller = $additionalTraveller->create($booking->id, $customer->id);
                $this->logging && Log::info('Additional Traveller created', [$newTraveller]);
            } else {
                Log::error('Invalid token when creating additional traveller pivot record for customer', [$token, $customer]);
            }
        }
        return $customer;
    }

    private function update_addresses($request, $customer)
    {
        $addressRepo = new AddressRepository();
        if (!$customer->home_address_id) {
            $addressIds = $this->create_addresses($request);
            $customer->home_address_id = $addressIds['home_address_id'];
            $customer->billing_address_id = $addressIds['billing_address_id'];

            return $addressIds;
        }

        if ($customer->home_address_id) {
            $newHomeAddress = [
                'id' => $customer->home_address_id,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3' => $request->address_line_3,
                'town' => $request->town,
                'region' => $request->region,
                'country' => $request->country,
                'postcode' => $request->postcode
            ];
            $home_address = $addressRepo->update($newHomeAddress);
            $home_address_id = $home_address['id'];
        } else {
            $home_address_id = 0;
        }
        if ($request->same_address) {
            $newBillingAddress = [
                'id' => $customer->billing_address_id,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3' => $request->address_line_3,
                'town' => $request->town,
                'region' => $request->region,
                'country' => $request->country,
                'postcode' => $request->postcode
            ];
        } else {
            $newBillingAddress = [
                'id' => $customer->billing_address_id,
                'address_line_1' => $request->billing_address_line_1,
                'address_line_2' => $request->billing_address_line_2,
                'address_line_3' => $request->billing_address_line_3,
                'town' => $request->billing_town,
                'region' => $request->billing_region,
                'country' => $request->billing_country,
                'postcode' => $request->billing_postcode
            ];
        }
        if ($customer->billing_address_id) {
            $billing_address = $addressRepo->update($newBillingAddress);
        } else {
            $billing_address = $addressRepo->create($newBillingAddress);
        }
        $billing_address_id = $billing_address->id;

        return [
            'home_address_id' => $home_address_id, 
            'billing_address_id' => $billing_address_id
        ];
    }

    /**
     * create_addresses()
     * makes new address records and returns their id values in an updated $customer object
     *
     * @param [type] $request
     * @param [type] $customer
     * @return Object ($customer)
     */
    private function create_addresses($request) {
        Log::info('** Creating Home Address ');
        $addressRepo = new AddressRepository();
        $address_record = [
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'address_line_3' => $request->address_line_3,
            'town' => $request->town,
            'region' => $request->region,
            'country' => $request->country,
            'postcode' => $request->postcode,
            'same_adress' => $request->same_address
        ];
        $home_address = $addressRepo->create($address_record);
        if (isset($home_address) && isset($home_address->id)) {
            $home_address_id = $home_address->id;
        } else {
            throw new \Exception('Can not create an address with ', $address_record);
        }
        if ($request->same_address) {
            $billingAddressRecord = [
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3' => $request->address_line_3,
                'town' => $request->town,
                'region' => $request->region,
                'country' => $request->country,
                'postcode' => $request->postcode
            ];
        } else {
            $billingAddressRecord = [
                'address_line_1' => $request->billing_address_line_1,
                'address_line_2' => $request->billing_address_line_2,
                'address_line_3' => $request->billing_address_line_3,
                'town' => $request->billing_town,
                'region' => $request->billing_region,
                'country' => $request->billing_country,
                'postcode' => $request->billing_postcode
            ];
        }
        $addressRepo = new AddressRepository();
        $billing_address = $addressRepo->create(
            $billingAddressRecord,
            'billing'
        );
        $billing_address_id = $billing_address->id;

        return [
            'home_address_id' => $home_address_id, 
            'billing_address_id' => $billing_address_id
        ];
    }

    public function updateLoginToken(Request $request) 
    {
        $email = $request->email;
        $login_token = $request->login_token;

        $customers = new Customer();
        $customer = $customers->where('email_address', $email)->first();
        if (empty($customer)) {
            Log::info('updateLoginToken, no customer for: ' . $email);
            return json_encode(['success' => false]);
        }
        $customer->login_token = $login_token;
        Log::info('setting login token'. $login_token, $customer->toArray());

        if ($customer->login_token) {
            $customer->save();
        } else {
            Log::info('NO LOGIN TOKEN TO UPDATE:' . $request->login_token);
        }

        return json_encode(['success' => true, 'customer' => $customer]);
    }

    /**
     * leadTraveller - save the leadTraveller data
     * does not appear to be used???
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function leadTraveller(Request $request)
    {
        $this->logging == 'customers' && Log::info('leadTraveller', $request->toArray());
        $customer = $this->storeOrUpdateCustomer($request, $request->booking_token, true);

        // $booking = new BookingRepository();
        // $findBooking = $booking->findBookingByToken($request->booking_token);
        // Log::debug('======= >>>>> findBooking', [$findBooking]);
        // if (empty($findBooking)) {
        //     Log::debug('tour record', [$request->tour_id]);
        //     Log::debug('====>>> creating a new booking with '.$request->tour['id'] . '  token:'. $request->booking_token);
        //     $customer['booking'] = $booking->create($customer->id, $request->tour['id'], $request->booking_token);
        // }

        return json_encode(['success' => true, 'customer' => $customer]); //, 'orderCustomer' => $orderCustomer]);
    }

    /**
     * additionalTraveller - save the additionalTraveller data
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function additionalTraveller(Request $request)
    {
        Log::debug('>>> additionalTraveller', $request->toArray());
        $customer = $this->storeOrUpdateCustomer($request, $request->booking_token, false);
        
        return json_encode(['success' => true, 'customer' => $customer]); //, 'orderCustomer' => $orderCustomer]);
    }

    public function loadAdditionalTravellers($token) {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (!$booking || !$booking->id) {
            throw new \Exception('loadAdditionalTravellers: booking not found '.$token);
        }
        $additionalTraveller = new AdditionalTraveller();
        $additionalTravellers = $additionalTraveller->where('booking_id', $booking->id)->get();
        foreach($additionalTravellers as $n => $traveller) {
            $additionalTravellers[$n] = Customer::findOrFail($traveller->customer_id);
        }
        return json_encode(['success' => true, 'travellers' => $additionalTravellers]);
    }

    public function removeAdditionalTraveller(Request $request)
    {
        $request->validate([
            'customer_id' => 'required | exists:customers,id',
            'booking_token' => 'required | exists:bookings,token'
        ]);

        $booking_token = $request->booking_token;
        $customer_id = $request->customer_id;

        $booking = Booking::where('token', $booking_token)->first();
        $customer = Customer::find($booking->customer_id);
        if ($booking->id && $customer->id) {
            AdditionalTraveller::where('booking_id', $booking->id)->where('customer_id', $customer_id)->delete();
        }

        return json_encode(['success' => true]);
    }
}
