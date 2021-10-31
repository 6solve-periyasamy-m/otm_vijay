<?php

// Booking Customer Controller: BOOKING FORM Updating API
// Lead and Additional Traveller API
// TODO: are these functions now redundant?

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Customer;

use Illuminate\Http\Request;
use App\Models\OrdersCustomer;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repository\AddressRepository;
use App\Repository\CustomerRepository;
use App\Http\Controllers\ApiController;
use App\Repository\OrdersCustomerRepository;

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
     * @param boolean $isLead
     * @return JSON (Customer object)
     */
    private function storeOrUpdateCustomer($request, $isLead = false)
    {
        $this->logging == 'customers' && Log::info('search for '. $request->email_address);

        $customer = new Customer();
        $customerExists = $customer->where('email_address', $request->email_address)->first();

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
                $billingValidated = $request->validate([
                        'billing_town' => 'required',
                        'billing_country' => 'required',
                        'billing_postcode' => 'required',
                        'billing_address1' => 'required'
                    ]);
                if ($this->logging == 'customers') {
                    Log::info('Billing Address Customer Validation passed', $validated);
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
        if ($isLead) {
Log::info('address fields in request', [$request->address_line_1]);
            $addressRepo = new AddressRepository();
            if (isset($customerExists) && $customerExists->home_address_id) {
                Log::info('**** getting home address for ', $customerExists->toArray());
                $currentAddress = $addressRepo->get($customerExists->home_address_id);
                if (empty($currentAddress)) {
                    throw new \Exception('???current address is null after a get???');
                }
                Log::info('what I found ', $currentAddress);
                $home_address = $addressRepo->update($currentAddress);
            } else {
                $home_address = $addressRepo->create(
                    [
                        'address_line_1' => $request->address_line_1,
                        'address_line_2' => $request->address_line_2,
                        'address_line_3' => $request->address_line_3,
                        'town' => $request->town,
                        'region' => $request->region,
                        'country' => $request->country,
                        'postcode' => $request->postcode,
                        'same_adress' => $request->same_address
                    ]
                );
                Log::info('Creating Home_address', $home_address->toArray());
            }

            $customer->home_address_id = $home_address->id;

            if (!$request->same_address) {
                if ($customerExists->business_address_id) {
                    $billing_address = $addressRepo->get($customerExists->business_address_id);
                } else {
                    $billing_address = $addressRepo->create(
                        [
                            'address_line_1' => $request->billing_line_1,
                            'address_line_2' => $request->billing_line_2,
                            'address_line_3' => $request->billing_line_3,
                            'town' => $request->billing_town,
                            'region' => $request->billing_region,
                            'country' => $request->billing_country,
                            'postcode' => $request->billing_postcode
                        ]
                    );
                }
                $customer->billing_address_id = $billing_address->id;
            } else {
                $customer->billing_address_id = $customer->home_address_id;
            }
        } else {
            $customer->home_address_id = 0;
            $customer->billing_address_id = 0;
            $customer->country = '-';
        }
        $customerRepo = new CustomerRepository();
        $customerData = [
            'home_address_id' => $customer->home_address_id,
            'billing_address_id' => $customer->billing_address_id,
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
            'login_token' => $request->login_token
        ];

        if ($customerExists) {
            $this->logging == 'customers' && Log::info('customer exists record ', $customerExists->toArray());
            $customer = $customerExists;
            $customer = $customerRepo->update($customerData);
        } else {
            // $customer->email_address = $request->email_address;
            Log::info('create customer with ', $customerData);
            $customer = $customerRepo->create($customerData);
        }
        if (isset($home_address)) {
            Log::info('check address vars ', [$home_address]);
            $customer->home_address = $home_address;
        }
        if (isset($business_address)) {
            Log::info('check business address vars ', [$business_address]);
            $customer->business_address = $business_address;
        }
        return $customer;
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
        $customer = $this->storeOrUpdateCustomer($request, true);
       // $orderCustomer = $this->storeOrUpdateOrderCustomer($customer, $request, true);

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
        $this->logging == 'customers' && Log::info('additionalTraveller', $request->toArray());
        $customer = $this->storeOrUpdateCustomer($request, false);
       // $orderCustomer = $this->storeOrUpdateOrderCustomer($customer, $request, false);
        
        return json_encode(['success' => true, 'customer' => $customer]); //, 'orderCustomer' => $orderCustomer]);
    }

    public function removeAdditionalTraveller(Request $request)
    {

        // TODO: use COD instead of orderCustomer
        $order_customer_id = $request->order_customer_id;
        $this->logging == 'customers' && Log::info('removing Additional Traveller order_customer_id:' . $order_customer_id);
        $orderCustomer = OrdersCustomer::find($order_customer_id);
        if (empty($orderCustomer)) {
            return json_encode(['success' => false, $request]);
        }
        $customer = Customer::find($orderCustomer->customer_id);

        $orderCustomer->deleted_at = date('Y-m-d H:i:s');
        $orderCustomer->save();

        return json_encode(['success' => true, 'customer' => $customer]);
    }
}
