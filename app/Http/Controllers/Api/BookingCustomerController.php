<?php

// Booking Customer Controller: BOOKING FORM Updating API
// Lead and Additional Traveller API
// TODO: are these functions now redundant?

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\OrdersCustomer;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApiController;
use App\Repository\OrdersCustomerRepository;

class BookingCustomerController extends ApiController
{
    protected $logging = 'customer';

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
     * as it is not used UNTIL the customer has a login and then the customer is authneticated
     *
     * @param [type] $request
     * @param boolean $isLead
     * @return JSON (Customer object)
     */
    private function storeOrUpdateCustomer($request, $isLead = false)
    {
        $customer = new Customer();
        //does this customer already exist?
        $this->logging == 'customers' && Log::info('search for '. $request->email_address);
        $customerExists = $customer->where('email_address', $request->email_address)->first();

        if ($customerExists) {
            if ($this->logging) {
                $this->logging == 'customers' && Log::info('customer exists record ', $customerExists->toArray());
            }
            $customer = $customerExists;
        } else {
            $customer->email_address = $request->email_address;
        }
        Log::info('validating lead traveller?', [$isLead]);

        // validation
        if ($isLead) {
            $validated = $request->validate([
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
                'postcode' => 'required']);
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

        $customer->title = $request->title;
        $customer->first_name = $request->first_name;
        $customer->middle_names = $request->middle_names;
        $customer->last_name = $request->last_name;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->mobile_number = $request->mobile_number;
        $customer->other_phone_number = $request->other_phone_number;
        $customer->gender = $request->gender;
        if ($isLead) {
            $customer->address_line_1 = $request->address_line_1;
            $customer->address_line_2 = $request->address_line_2;
            $customer->address_line_3 = $request->address_line_3;
            $customer->town = $request->town;
            $customer->country = $request->country;
            $customer->postcode = $request->postcode;
            $customer->same_address = $request->same_address;
            if (!$customer->same_address) {
                $customer->billing_line_1 = $request->billing_line_1;
                $customer->billing_line_2 = $request->billing_line_2;
                $customer->billing_line_3 = $request->billing_line_3;
                $customer->billing_town = $request->billing_town;
                $customer->billing_country = $request->billing_country;
                $customer->billing_postcode = $request->billing_postcode;
            }
        } else {
            $customer->country = '-';
        }
        if ($this->logging) {
            $this->logging == 'customers' && Log::info('saving customer details ', $customer->toArray());
        }
        $customer->save();

        return $customer;
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
        $customer = $this->storeOrUpdateCustomer($request);
        $orderCustomer = $this->storeOrUpdateOrderCustomer($customer, $request, true);

        return json_encode(['customer' => $customer, 'orderCustomer' => $orderCustomer]);
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
        $customer = $this->storeOrUpdateCustomer($request);
        $orderCustomer = $this->storeOrUpdateOrderCustomer($customer, $request, false);
        
        return json_encode(['success' => true, 'customer' => $customer, 'orderCustomer' => $orderCustomer]);
    }

    public function removeAdditionalTraveller(Request $request)
    {
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
