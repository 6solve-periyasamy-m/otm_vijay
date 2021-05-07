<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Tour;
use App\Models\Order;
use App\Models\Flight;
use App\Models\Customer;
use App\Models\OrdersCustomer;
use App\Models\CustomerOrderDetail;
use App\Models\FlightInventory;
use App\Models\FlightInventoryTour;

class BookingController extends ApiController
{

    private function getFlightTour($flight_inventory_tour_id)
    {   
        $flightTours = new FlightInventoryTour();
        $flightTour = $flightTours->find($flight_inventory_tour_id);
        Log::info('flightTour', $flightTour->toArray());
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
        $orderCustomers = new OrdersCustomer();
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

    /**
     * bookFlightDetails
     * save flight details for a pax tour flight
     * 1. create / update orders_customers
     * 2. create customer_order_details (audit)
     * 3. create / update 8orders_flights
     * @param tour
     * @param passenger
     * @param flight (we are sending in the flight->id - which should be the flightInventoryTour record id)
     */
    public function bookFlightDetails($customer_id, $tour_id, $order_id, $flight_type, $flight_inventory_tour_id, $custom, $reference)
    {
        Log::info('bookFlightDetails parameters:', [$customer_id, $tour_id, $order_id, $flight_type, $flight_inventory_tour_id, $custom, $reference]);

        $customers = new Customer();
        $tours = new Tour();
        $flights = new Flight();
        $flightInventory = new FlightInventory();

        $flightTour = $this->getFlightTour($flight_inventory_tour_id);
        $tour = $tours->find($tour_id);
        $order = $this->getOrder($order_id);
        $rejection = 0;
        if ($tour->id !== $order->tour_id) {
            $rejection = 302;
            $status = 'Invalid Order';
        } else {
            $status = 'Order and Tour agree';
        }
        $orderCustomer = $this->getOrderCustomer($order, $customer_id);
        $this->storeOrUpdateCustomerOrderDetail($order, $orderCustomer, $flightTour, $flight_type, $custom, $reference);
        if ($rejection || !$customer_id || !$flightTour->id) {
            return [
                'status' => $rejection,
                'message' => $status
            ];
        }
        Log::info('booking flight details:', [$tour_id, $flightTour->id, $customer_id]);
        return [
            'status' => $rejection,
            'message' => $status
        ];
    }

    public function storeOrUpdateCustomerOrderDetail($order, $orderCustomer, $flightTour, $flightType, $custom, $reference)
    {

        // customer_order_details
        $customer_order_details = new CustomerOrderDetail();
        $customer_order_detail = $customer_order_details
            ->where('orders_customer_id', $orderCustomer->id)
            ->where('order_id', $order->id)
            ->where('type', $flightType)
            ->where('reference', $reference)
            //->where('inventory_id', $flightTour->flight_inventory_id)
            ->first();
        if (empty($customer_order_detail)) {
            $customer_order_detail = $customer_order_details;
            $status = 'original';
        } else {
            $status = 'updated';
        }
        // always make a fresh record, so we can track how the form works
        // $customer_order_detail = new CustomerOrderDetail();
        $customer_order_detail->orders_customer_id = $orderCustomer->id;
        $customer_order_detail->inventory_id = $flightTour->flight_inventory_id;
        $customer_order_detail->order_id = $order->id;
        $customer_order_detail->type = $flightType;
        $customer_order_detail->inventory_tour_id = $flightTour->id;
        $customer_order_detail->date_time = now();
        $customer_order_detail->status = $status;
        // these parameters have yet to be established
        // reference - the leads hash?
        // addon if there is a cost associated: where from?
        // cost for addon
        $customer_order_detail->reference = $reference;
        $customer_order_detail->addon = $custom;
        $customer_order_detail->cost = 0;

        Log::info('saving customer_order_detail record', $customer_order_detail->toArray());
        $customer_order_detail->save();
    }
    
    /**
     * storeOrUpdateOrderCustomer - stores the orderCustomer data from the booking form
     *
     * @param Customer $customer
     * @param Request $request
     * @param boolean $isLead
     * @return JSON (record saved)
     */
    private function storeOrUpdateOrderCustomer($customer_id, Request $request, $isLead = false) 
    {
        if (empty($request->order_id)) {
            throw new \Exception('storeOrUpdateOrderCustomer has no order ID');
        }
        $ordersCustomer = new OrdersCustomer();
        Log::info('loading ordercustomer  order '. $request->order_id.' customer: '.$customer_id);
        // $ordersCustomerExists = $ordersCustomer
        //     ->where('order_id', $request->order_id)
        //     ->where('inventory_tour_id', $request->inventory_tour_id)
        //     ->where('customer_id', $customer_id)
        //     ->first();
        // if ($ordersCustomerExists) {
        //     $ordersCustomer = $ordersCustomerExists;
        //     Log::info('orderCustomer record', $ordersCustomerExists->toArray());
        //     $this->updateOrderCustomerFields($ordersCustomer, $request);
        //     Log::info('ordercustomer exists, updating');
        // } else {
            $ordersCustomer->order_id = $request->order_id;
            $ordersCustomer->customer_id = $customer_id;
            $ordersCustomer->inventory_tour_id = $request->inventory_tour_id;
            Log::info('loading ordercustomer  order '. $request->order_id.' customer: '.$customer_id);
//        }
        $ordersCustomer->is_lead_booker = $isLead;
        $ordersCustomer->travel_insurer = null;
        $ordersCustomer->policy_number = null;
        $ordersCustomer->save();

        return $ordersCustomer;
    }

    /**
     * storeOrUpdateCustomer
     *
     * @param [type] $request
     * @param boolean $isLead
     * @return JSON (Customer object)
     */
    private function storeOrUpdateCustomer($request, $isLead = false) 
    {
        $customer = new Customer();
        //does this customer already exist?
        $customerExists = $customer->where('email_address', $request->email_address)->first();
        if ($customerExists) {
            if ($this->logging) {
                Log::info('customer exists record ', $customerExists->toArray());
            }
            $customer = $customerExists;
        } else {
            $customer->email_address = $request->email_address;
        }
        $customer->title = $request->title;
        $customer->first_name = $request->first_name;
        $customer->middle_names = $request->middle_names;
        $customer->last_name = $request->last_name;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->mobile_number = $request->mobile_number;
        $customer->other_phone_number = $request->other_phone_number;
        $customer->password = null;
        $customer->gender = $request->gender;
        if ($isLead) {
            $customer->address_line_1 = $request->address_line_1;
            $customer->address_line_2 = $request->address_line_2;
            $customer->address_line_3 = $request->address_line_3;
            $customer->billing_line_1 = isset($request->billing_line_1) ? $request->billing_line_1 : $request->address_line_1;
            $customer->billing_line_2 = isset($request->billing_line_2) ? $request->billing_line_2 : $request->address_line_2;
            $customer->billing_line_3 = isset($request->billing_line_3) ? $request->billing_line_3 : $request->address_line_3;
            $customer->town = $request->town;
            $customer->country = $request->country;
            $customer->postcode = $request->postcode;
            $customer->billing_town = isset($request->billing_town) ? $request->billing_town : $request->town;
            $customer->billing_country = isset($request->billing_country) ? $request->billing_country : $request->country;
            $customer->billing_postcode = isset($request->billing_postcode) ? $request->billing_postcode : $request->postcode;
        }
        if ($this->logging) {
            Log::info('saving customer detaisl ', $customer->toArray());
        }
        $customer->save();

        return $customer->id;
    }

    /**
     * leadTraveller - save the leadTraveller data
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function leadTraveller(Request $request) 
    {
        if ($this->logging) {
            Log::info('leadTraveller', $request->toArray());
        }
        $customer = $this->storeOrUpdateCustomer($request, true);
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
        if ($this->logging) {
            Log::info('additionalTraveller', $request->toArray());
        }

        $customer = $this->storeOrUpdateCustomer($request);
        $orderCustomer = $this->storeOrUpdateOrderCustomer($customer, $request, false);

        return json_encode(['customer' => $customer, 'orderCustomer' => $orderCustomer]);
    }

        /**
     * updateOrderCustomer - adds fields to existing orderCustomer record for a single traveller
     *
     * @param [type] $ordersCustomer (object)
     * @param Request $request
     * @return void
     */
    private function updateOrderCustomerFields($ordersCustomer, Request $request) 
    {
        if (!empty($request->tour['base_price_per_person'])) {
            $ordersCustomer->tour_cost = $request->tour['base_price_per_person'];
        }
        if (!empty($request->tour['single_occupancy_surcharge'])) {
            $ordersCustomer->single_occupancy_surcharge = $request->tour['single_occupancy_surcharge'];
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
        if ($this->logging) {
            Log::info('create order for tour ' . $request->tour);
            Log::info('order id ', $order->toArray());
        }
        return response()->json(["success" => true, "order" => $order]);
    }
}
