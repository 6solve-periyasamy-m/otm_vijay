<?php

// Booking Controller: general updating API
// TODO: REFACTOR

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Repository\ActionsRepository;
use App\Models\Tour;
use App\Models\Order;
use App\Models\Flight;
use App\Models\Customer;
use App\Models\OrderCustomer;
use App\Models\CustomerOrderDetail;
use App\Models\FlightInventory;
use App\Models\FlightInventoryTour;
use App\Repository\CustomerOrderDetailRepository;

class BookingController extends ApiController
{
    protected $logging = 'flights';

    private function getFlightTour($flight_inventory_tour_id)
    {   
        $flightTours = new FlightInventoryTour();
        $flightTour = $flightTours->find($flight_inventory_tour_id);
        $this->logging == 'flights' && Log::info('flightTour', $flightTour->toArray());
        return $flightTour;
    }
    private function getOrder($order_id)
    {
        $orders = new Order();
        $order = $orders->find($order_id);
        return $order;
    }
    private function getOrderCustomer($order, $customer_id) 
    {
        $orderCustomers = new OrderCustomer();
        $customer_id = 0 + $customer_id;
        $orderCustomer = $orderCustomers
                ->where('order_id', $order->id)
                ->where('customer_id', $customer_id)
                ->first();
        if (!$orderCustomer) {
            $rejection = 403;
            $status = 'No Record or additional traveller order';
            Log::info('orderCustomer not found order_id'.$order->id.' customer_id'.$customer_id);
        } else {
            Log::info('orderCustomer found', $orderCustomer->toArray());
        }
        return $orderCustomer;
    }

    private function removeCustomerOrderDetail($orderCustomer, $inventory_tour_id, $type, $custom, $reference) {
        $cod = new CustomerOrderDetail();
        $result = $cod->where('order_customer_id', $orderCustomer->id)
            ->where('type', ucfirst($type))
            ->where('addon', $custom)
            ->where('inventory_tour_id', $inventory_tour_id)
            ->where('reference', $reference)
            ->whereNull('deleted_at')
            ->first();

        if ($result) {
            $this->logging && Log::info('removing ', $result->toArray());
            $result->delete();
        } 
        return $result;
    }
    /**
     * Remove flight booking 
     *
     * @param Request $request
     * @return JSON response 
     */
    public function removeFlightBooking(Request $request)
    {
        $validated = $request->validate([
            'order_customer_id' => 'required',
            'inventory_tour_id' => 'required',
            'token' => 'required'
        ]);
        $order_customer_id = $request->order_customer_id;
        $inventory_tour_id = $request->inventory_tour_id;
        $orderCustomers = new OrderCustomer();
        $orderCustomer = $orderCustomers->find($order_customer_id);

        $token = $_COOKIE['OTM_booking_order_token'];
        if ($token !== $request->token) {
            throw new \Exception('Booking token mismatch I have:'. $token . ' request has:'.$request->token);
        }
        if (empty($orderCustomer) || empty($orderCustomer->order_id)) {
            throw new \Exception('No order exists!');
        }
        $orders = new Order();
        $order = $orders->find($orderCustomer->order_id);
        $this->logging && Log::info('order for removal', $order->toArray());

        if ($token !== $order->token) {
            throw new \Exception('Order token mismatch');
        }
        // $order_id = $order->id;
        $type = $request->flight_type;
        $custom = $request->custom;

        // $inventory_tour_id = $request->inventory_tour_id;
        // $reference = $order->reference;
        $result = $this->removeCustomerOrderDetail($orderCustomer, $inventory_tour_id, $type, $custom, $order->token);
    
        return json_encode(['success' => true, 'flight' => $result, 'orderCustomer' => $orderCustomer]);
    }
    /**
     * bookFlightDetails
     * save flight details for a pax tour flight
     * 1. create / update order_customers
     * 2. create customer_order_details (audit)
     * 3. create / update 8orders_flights
     * @param tour
     * @param passenger
     * @param flight (we are sending in the flight->id - which should be the flightInventoryTour record id)
     */
     public function bookFlightDetails(Request $request)
    {
        // validation
        $validated = $request->validate([
            'customer_id' => 'required',
            'tour_id' => 'required',
            'order_id' => 'required',
            'flight_type' => 'required',
            'inventory_tour_id' => 'required',
            'custom' => 'required',
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $order_id = $request->order_id;
        $flight_type = $request->flight_type;
        $inventory_tour_id = $request->inventory_tour_id;
        $custom = $request->custom;

        $token = $_COOKIE['OTM_booking_order_token'];
        if ($token !== $request->token) {
            throw new \Exception('Booking token mismatch');
        }
        $token = $request->token;

        $this->logging == 'flights' && Log::info('bookFlightDetails parameters:', [$customer_id, $tour_id, $order_id, $flight_type, $inventory_tour_id, $custom, $token]);

        $tours = new Tour();
        $rejection = 0;

        // validate parameters are valid
        if ($inventory_tour_id) {
            $flightTour = $this->getFlightTour($inventory_tour_id);
            if(!$flightTour) {
                $rejection = 404;
                $status = 'Invalid Flight Tour record';
            }
        }
        $tour = $tours->find($tour_id);
        $order = $this->getOrder($order_id);
        if ($tour->id !== $order->tour_id) {
            $rejection = 302;
            $status = 'Invalid Tour/Order';
        } else {
            $status = 'Order ';
        }

        $orderCustomer = $this->getOrderCustomer($order, $customer_id);
        $this->logging === 'flights' && Log::info('bookFlightDetails order:', $orderCustomer->toArray());

        if ($inventory_tour_id) {
            $result = $this->storeOrUpdateCustomerOrderDetail($orderCustomer, $flightTour, $flight_type, $custom, $token);
            $this->logging === 'flights' && Log::info('storeOrUpdateCustomerOrderDetail returned!', $result);
        } else {
            $result = $this->removeCustomerOrderDetail('flight', $order, $orderCustomer, $flight_type, $custom, $token);
            $this->logging === 'flights' && Log::info('removeCustomerOrderDetail returned!');
        }
        if (!$result) {
            $status .= ' error updating!';
        } else {
            $status .= ' update appears successful';
        }

        if ($rejection || !$customer_id ) {
            return [
                'status' => $rejection,
                'message' => $status
            ];
        }
        
        if ($rejection) {
            $this->logging == 'flights' && Log::info('booking flight details:', [$tour_id, $flightTour->id, $customer_id]);
            ActionsRepository::log('Booking Order '.$status, $customer_id, $order_id);
        }

        return [
            'status' => $rejection,
            'message' => $status
        ];
    }

    // private function findCustomerOrderDetailsByOrderId($order_customer_id, $order_id, $flight_type, $reference, $addon)
    // {
    //     $model = new CustomerOrderDetail();
    //     $customer_order_details = $model
    //         ->where('order_customer_id', $order_customer_id)
    //         ->where('order_id', $order_id)
    //         ->where('type', $flight_type)
    //         ->where('reference', $reference)
    //         ->where('addon', $addon)
    //         ->get();

    //     return $customer_order_details;
    // }
    private function findCustomerOrderDetailByInventoryTourId($order_customer_id, $inventory_tour_id, $flight_type, $reference, $addon)
    {
        $model = new CustomerOrderDetail();
        $this->logging == 'orders' && Log::info('findCustomerOrderDetailByInventoryTourId', [$order_customer_id, $inventory_tour_id, $flight_type, $reference, $addon]);
        $customer_order_detail = $model
            ->select('customer_order_details.*')
            ->join('flight_inventory_tour', 'flight_inventory_tour.id', 'customer_order_details.inventory_tour_id')
            ->join('tours', 'flight_inventory_tour.tour_id', 'tours.id')
            ->join('order_customers', 'order_customers.id', 'customer_order_details.order_customer_id')
            ->join('orders', 'order_customers.order_id', 'orders.id')
            ->whereNull('customer_order_details.deleted_at')
            ->where('customer_order_details.order_customer_id', $order_customer_id)
            ->where('customer_order_details.type', $flight_type)
            ->where('addon', $addon)
            ->where('orders.token', $reference)
            ->first();
            
        if (isset($customer_order_detail)) {
            $this->logging == 'orders' && Log::info('found : ', $customer_order_detail->toArray());
        } else {
            $this->logging == 'orders' && Log::info('nothing found! ');
        }

        return $customer_order_detail;
    }

    /**
     * storeOrUpdate COD
     * ($orderCustomer, $flightTour, $flight_type, $custom, $reference)
     *
     * @param [type] $orderCustomer
     * @param [type] $flightTour
     * @param [type] $flightType
     * @param [type] $addon
     * @param [type] $reference
     * @return void
     */
    public function storeOrUpdateCustomerOrderDetail($orderCustomer, $flightTour, $flightType, $addon, $reference)
    {
        $customer_order_detail = $this->findCustomerOrderDetailByInventoryTourId($orderCustomer->id, $flightTour->flight_inventory_id, $flightType, $reference, $addon);
        // // when setting an group order, remove any addon that matches it
        // if (!$addon) {
        //     $addon_cod = $this->findCustomerOrderDetailByInventoryTourId($orderCustomer->id, $flightTour->flight_inventory_id, $flightType, $reference, 1);
        //     if ($addon_cod) {
        //         ActionsRepository::log('Customer Order Detail removing ', $orderCustomer->id, $orderCustomer->order_id, $reference);
        //         $addon_cod->delete();
        //         //$addon_cod->status = 'deleted';
        //         //$addon_cod->save();
        //     }
        // }

        if (!isset($customer_order_detail)) {
            $customer_order_detail = new CustomerOrderDetail();
            ActionsRepository::log('CREATE new Flight: Customer Order Detail', $orderCustomer->order_id, $flightTour->flight_inventory_id, $reference);
            $status = 'created';
        } else {
            $status = 'updated';
            ActionsRepository::log('UPDATE Flight: Customer Order Detail', $orderCustomer->order_id, $flightTour->flight_inventory_id, 'Flight Inventory ID?='.$flightTour->id);
            // anything to update?
            if ($customer_order_detail->order_customer_id === $orderCustomer->id
                && $customer_order_detail->inventory_tour_id === $flightTour->id
                && $customer_order_detail->type === $flightType
                && $customer_order_detail->addon === intval($addon)
                && $customer_order_detail->reference === $reference) {
                ActionsRepository::log('Accessed Customer Order Detail, nothing to update', $orderCustomer->order_id, $flightTour->flight_inventory_id, $reference);
                //return;
            }
        }
        $customer_order_detail->order_customer_id = $orderCustomer->id;
        $customer_order_detail->type = $flightType;
        $customer_order_detail->inventory_tour_id = $flightTour->id;
        $customer_order_detail->date_time = date('Y-m-d H:i:s');
        $customer_order_detail->status = $status;
        $customer_order_detail->reference = $reference;
        $customer_order_detail->addon = $addon;
        $customer_order_detail->cost = isset($flightTour->sales_price) ? $flightTour->sales_price : 0;

        $actionDescription = 'Customer Order Detail ';
        if ($customer_order_detail->addon) {
            $actionDescription .= ' addon booking ';
        } else {
            $actionDescription .= ' group booking ';
        }
        ActionsRepository::log($actionDescription, $orderCustomer->order_id, $flightTour->flight_inventory_id, $customer_order_detail->status);

        $codRepo = new CustomerOrderDetailRepository();
        return $codRepo->storeCustomerOrderDetail($customer_order_detail);
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
        $orderCustomer = new OrderCustomer();
        $this->logging == 'orders' && Log::info('loading orderCustomer  order '. $request->order_id.' customer: '.$customer->id);
        $orderCustomerExists = $orderCustomer
            ->where('order_id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->first();
        if ($orderCustomerExists) {
            $orderCustomer = $orderCustomerExists;
            $this->logging == 'orders' && Log::info('orderCustomer record', $orderCustomerExists->toArray());
            $this->updateOrderCustomerFields($orderCustomer, $request);
            // Log::info('orderCustomer exists, updating');
        } else {
            $orderCustomer->order_id = $request->order_id;
            $orderCustomer->customer_id = $customer->id;
            $this->logging == 'orders' && Log::info('creating orderCustomer for order '. $request->order_id.' customer: '.$customer->id);
        }
        $orderCustomer->is_lead_booker = $isLead;
        $orderCustomer->travel_insurer = null;
        $orderCustomer->policy_number = null;
        $orderCustomer->save();

        return $orderCustomer;
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
                    if ($this->logging == 'customers') Log::info('Billing Address Customer Validation passed', $validated);
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
        if ($this->logging == 'customers') Log::info('Basic Customer Validation passed', $validated);

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
        $orderCustomer = OrderCustomer::find($order_customer_id);
        if (empty($orderCustomer)) {
            return json_encode(['success' => false, $request]);
        }
        $customer = Customer::find($orderCustomer->customer_id);

        $orderCustomer->deleted_at = date('Y-m-d H:i:s');
        $orderCustomer->save();

        return json_encode(['success' => true, 'customer' => $customer]);
    }
        /**
     * updateOrderCustomer - adds fields to existing orderCustomer record for a single traveller
     *
     * @param [type] $orderCustomer (object)
     * @param Request $request
     * @return void
     */
    private function updateOrderCustomerFields($orderCustomer, Request $request)
    {
        if (!empty($request->tour['base_price_per_person'])) {
            $orderCustomer->tour_cost = $request->tour['base_price_per_person'];
        }
        if (!empty($request->tour['single_occupancy_surcharge'])) {
            $orderCustomer->single_occupancy_surcharge = $request->tour['single_occupancy_surcharge'];
        }
    }
    
    /***
     * order section
     */
        /**
     * createOrder - makes an order every time booking form is accessed by URL, unless it already exists (via token or link)
     *
     * @param Request $request
     * @return JSON (order object)
     */
    public function createOrder(Request $request)
    {
        $order = new Order();
        $order->quote_id = null;
        $order->tour_id = $request->tour;
        $order->total_order_value = null;
        $order->notes = 'Created by '.$_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI'];
        $order->order_status_id = 1;
        $order->token = md5(uniqId());
        $order->save();
        // Order::insert([
        //     'tour_id' => $order->tour_id, 
        //     'notes' => $order->notes,
        //     'token' => $order->token]);
        $this->logging == 'orders' && Log::info('create order for tour ' . $request->tour);
        $this->logging == 'orders' && Log::info('order id ', $order->toArray());

        return response()->json(["success" => true, "order" => $order]);
    }
}
