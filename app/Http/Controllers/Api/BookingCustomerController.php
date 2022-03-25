<?php

namespace App\Http\Controllers\Api;

use Exception;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Address;

use Illuminate\Http\Request;

use App\Models\BookingTraveller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repository\AddressRepository;
use App\Repository\BookingRepository;
use App\Repository\CustomerRepository;
use App\Http\Controllers\ApiController;
use App\Repository\OrdersCustomerRepository;
use App\Repository\BookingTravellerRepository;

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
        // validation
        if ($isLead) {
            $validated = $request->validate([
                'email_address' => 'required | email:rfc,dns',
                'title' => 'required',
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha_dash',
                'date_of_birth' => 'required | date | before: 18 years ago',
                'mobile_number' => 'required',
                'gender' => 'required',
                'address_line_1' => 'required',
                'address_line_2' => 'required',
                'town' => 'required',
                'country_id' => 'required',
                'postcode' => 'required'
            ]);
            if (!$request->same_address) {
                Log::info('validation of billing address', [$request->billing_address_line_1]);
                $billingValidated = $request->validate([
                    'billing_town' => 'required',
                    'billing_country_id' => 'required',
                    'billing_postcode' => 'required',
                    'billing_address_line_1' => 'required'
                ]);
                if ($this->logging == 'customers') {
                    Log::debug('Billing Address Customer Validation passed', $billingValidated);
                }
            }
        } else {
            $validated = $request->validate([
                'email_address' => 'nullable | email:rfc,dns',
                'title' => 'required',
                'first_name' => 'required | alpha',
                'last_name' => 'required | alpha_dash',
                'date_of_birth' => 'required | date',
                'mobile_number' => 'required',
                'gender' => 'required'
            ]);
        }
        if ($this->logging == 'customers') {
            Log::debug('Basic Customer Validation passed', $validated);
        }

        $customerRepo = new CustomerRepository();
        $customerData = [
            'home_address_id' => isset($customer) ? $customer->home_address_id : null,
            'billing_address_id' => isset($customer) ? $customer->billing_address_id : null,
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
            'gender' => $request->gender,
            'address_line_1' => $request->address_line_1
        ];
        $customer = Customer::where('email_address', $request->email_address)->first();

        // if the customer exists, update the addresses
        if (isset($customer) && isset($customer->email_address)) {
            if ($isLead) {
                $addressIds = $this->update_addresses($request, $customer);
                // MAR record may have been created
                if ($addressIds['home_address_id']) {
                    $customerData['home_address_id'] = $addressIds['home_address_id'];
                }
                if ($customerData['billing_address_id']) {
                    $addressIds['billing_address_id'];
                }
            }
            $customer = $customerRepo->update($customerData);
        } else {
            $customerData['email_address'] = $request->email_address;
            if ($isLead) {
                // a new lead customer record creates the booking record and address records
                $addressIds = $this->create_addresses($request);
                $customerData['home_address_id'] = $addressIds['home_address_id'];
                $customerData['billing_address_id'] = $addressIds['billing_address_id'];
            } 
            // additional travellers have a dummy address_1 field (unless they already exist)
            if (!$isLead) {
                if (empty($customerData['address_line_1'])) {
                    $customerData['home_address_id'] = $this->create_minimal_address($customerData, 'Home address');
                }
                if (empty($customerData['billing_address_line_1'])) {
                    $customerData['billing_address_id'] = $this->create_minimal_address($customerData, 'Billing address');
                }
            }
            $customer = $customerRepo->create($customerData);
        }

        return $customer;
    }

    // if a customer is submitted with NULL address_id fields, means do not change an existing address
    // but ensure that a minimal address (type) exists for the customer
    private function create_minimal_address($customerData, $type)
    {
        /*
        if (empty($customerData)) {
            return;
        }
        if ($type == 'home' && $customerData['home_address_id'] !== null) {
            return;
        }
        if ($type == 'billing' && $customerData['billing_address_id'] !== null) {
            return;
        }
        */
        // do not update an address that already exists
        $address = new Address();
        if (isset($customerData['id']) && $address->where('customer_id', $customerData['id'])->count()) {
            return $address->id;
        }
        // MAR address record is default when no address supplied
        $address->address_line_1 = '(' .$customerData['email_address']. ') ' . $customerData['first_name'] . ' ' . $customerData['last_name'];
        $address->name = $type;
        $address->address_parent_id = 1;
        $address->save();

        return $address->id;
    }

    private function update_addresses($request, $customer)
    {
        $addressRepo = new AddressRepository();
        if (empty($customer)) {
            throw new Exception('BookingCustomerController::update_address, no customer');
        }

        // we get null address IDs i.e. for an additional traveller - create a minimal address
        // BUT this is an update: therefore we should have an address field and not having one is an error
        // HOWEVER: it is not a fatal condition, just create the MAR Record
        $this->logging == 'customer' && Log::warning('When update_addresses called, the customer home_address ID was null, creating the address:', [$customer]);
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
                'country_id' => $request->country_id,
                'postcode' => $request->postcode,
                'same_address' => $request->same_address,
                'address_parent_id' => 1,
                'name' => 'Home Address: ' . $customer->first_name . ' ' . $customer->last_name,
            ];
            $home_address = $addressRepo->update($newHomeAddress);
            $home_address_id = $home_address['id'];
        } else {
            $home_address_id = null;
        }
        if ($request->same_address) {
            $newBillingAddress = [
                'id' => $customer->billing_address_id,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3' => $request->address_line_3,
                'town' => $request->town,
                'region' => $request->region,
                'country_id' => $request->country_id,
                'postcode' => $request->postcode,
                'address_parent_id' => 1,
                'name' => 'Billing address: ' . $customer->first_name . ' ' . $customer->last_name,
            ];
        } else {
            $newBillingAddress = [
                'id' => $customer->billing_address_id,
                'address_line_1' => $request->billing_address_line_1,
                'address_line_2' => $request->billing_address_line_2,
                'address_line_3' => $request->billing_address_line_3,
                'town' => $request->billing_town,
                'region' => $request->billing_region,
                'country_id' => $request->billing_country_id,
                'postcode' => $request->billing_postcode,
                'address_parent_id' => $customer->id,
                'name' => 'Billing address: ' . $customer->first_name . ' ' . $customer->last_name,
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
     * @param [type] $request data
     * @return array home and billing address_ids
     */
    private function create_addresses(Request $request) {
        $this->logging == 'customer' && Log::debug('Create a new home address using '. $request->address_line_1);
        $addressRepo = new AddressRepository();
        $address_record = [
            'name' => 'Home address',
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'address_line_3' => $request->address_line_3,
            'town' => $request->town,
            'region' => $request->region,
            'country_id' => $request->country_id,
            'postcode' => $request->postcode,
            'same_adress' => $request->same_address
        ];
        $home_address = $addressRepo->create($address_record);
        if (isset($home_address) && isset($home_address->id)) {
            $home_address_id = $home_address->id;
        } else {
            Log::debug('Error creating address with record: ', $address_record);
            throw new \Exception('Can not create address ');
        }
        // if the same address flag is set, create the builling address with the same data
        if ($request->same_address) {
            $billingAddressRecord = [
                'name' => 'Billing address',
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3' => $request->address_line_3,
                'town' => $request->town,
                'region' => $request->region,
                'country_id' => $request->country_id,
                'postcode' => $request->postcode
            ];
        } else {
            $billingAddressRecord = [
                'name' => 'Billing address',
                'address_line_1' => $request->billing_address_line_1,
                'address_line_2' => $request->billing_address_line_2,
                'address_line_3' => $request->billing_address_line_3,
                'town' => $request->billing_town,
                'region' => $request->billing_region,
                'country_id' => $request->billing_country_id,
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
            Log::warning('BookingCustomerController::updateLoginToken: WARNING no customer for: ' . $email);
            return response()->json(['success' => false]);
        }
        $customer->login_token = $login_token;
        Log::info('setting login token'. $login_token, $customer->toArray());

        if ($customer->login_token) {
            $customer->save();
        } else {
            Log::info('NO LOGIN TOKEN TO UPDATE:' . $request->login_token);
        }
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    private function updateBookingTraveller($token, $customer) 
    {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (isset($booking)) {
            $bookingTravellerRepo = new BookingTravellerRepository();
            $traveller_id = $bookingTravellerRepo->create($booking->id, $customer->id);
            return $traveller_id;
        } else {
            return null;
        }
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
        Log::debug('BookingCustomerController:leadTraveller', [$request->booking_token]);
        $customer = $this->storeOrUpdateCustomer($request, $request->booking_token, true);
        $this->updateBookingTraveller($request->booking_token, $customer);

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    /**
     * bookingTraveller - save the BookingTraveller data
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function bookingTraveller(Request $request)
    {
        $this->logging == 'customers' && Log::debug('BookingTraveller', $request->toArray());
        $customer = $this->storeOrUpdateCustomer($request, $request->booking_token, false);
        $this->updateBookingTraveller($request->booking_token, $customer);

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    /**
     * loadTravellers
     *
     * @param STRING $token
     * @return JSON 
     */
    public function loadTravellers($token) {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (!$booking || !$booking->id) {
            return json_encode(["success" => false, "message" => "No booking yet"]);
    
            throw new \Exception('loadBookingTravellers: No booking found for token '.$token);
        }
        $traveller = new BookingTraveller();
        $travellers = $traveller->where('booking_id', $booking->id)->get();
        foreach($travellers as $n => $traveller) {
            $travellers[$n] = Customer::findOrFail($traveller->customer_id);
        }

        return response()->json(['success' => true, 'travellers' => $travellers]);
    }

    public function removeBookingTraveller(Request $request)
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
            BookingTraveller::where('booking_id', $booking->id)->where('customer_id', $customer_id)->delete();
        }

        return response()->json(['success' => true]);
    }
}

