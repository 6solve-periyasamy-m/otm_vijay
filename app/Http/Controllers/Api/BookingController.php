<?php

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
use App\Models\OrdersCustomer;
use App\Models\CustomerOrderDetail;
use App\Models\FlightInventory;
use App\Models\FlightInventoryTour;

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
    //    // /booking/flight/{customer}/{tour}/{order}/{flight_type}/{flight}/{custom}/{reference}
    public function bookFlightDetails($customer_id, $tour_id, $order_id, $flight_type, $flight_inventory_tour_id, $custom, $reference)
    {
        $this->logging == 'flights' && Log::info('bookFlightDetails parameters:', [$customer_id, $tour_id, $order_id, $flight_type, $flight_inventory_tour_id, $custom, $reference]);

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
            $status = 'Order ';
        }
        
        $orderCustomer = $this->getOrderCustomer($order, $customer_id);
        $result = $this->storeOrUpdateCustomerOrderDetail($order, $orderCustomer, $flightTour, $flight_type, $custom, $reference);
        if (!$result) {
            $status .= ' error updating!';
        } else {
            $status .= ' update appears successful';
        }

        if ($rejection || !$customer_id || !$flightTour->id) {
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

    // private function findCustomerOrderDetailsByOrderId($orders_customer_id, $order_id, $flight_type, $reference, $addon)
    // {
    //     $model = new CustomerOrderDetail();
    //     $customer_order_details = $model
    //         ->where('orders_customer_id', $orders_customer_id)
    //         ->where('order_id', $order_id)
    //         ->where('type', $flight_type)
    //         ->where('reference', $reference)
    //         ->where('addon', $addon)
    //         ->get();

    //     return $customer_order_details;
    // }

    private function findCustomerOrderDetailByInventoryTourId($orders_customer_id, $inventory_tour_id, $flight_type, $reference, $addon)
    {
        $model = new CustomerOrderDetail();
        $this->logging == 'orders' && Log::info('findCustomerOrderDetailByInventoryTourId', [$orders_customer_id, $inventory_tour_id, $flight_type, $reference, $addon]);
        $customer_order_detail = $model
            ->select('customer_order_details.*')
            ->join('flight_inventory_tour', 'flight_inventory_tour.id', 'customer_order_details.inventory_tour_id')
            ->join('tours', 'flight_inventory_tour.tour_id', 'tours.id')
            ->join('orders', 'customer_order_details.order_id', 'orders.id')
            ->where('customer_order_details.orders_customer_id', $orders_customer_id)
            ->where('customer_order_details.type', $flight_type)
            ->where('orders.token', $reference)
            ->first();
            
        if (isset($customer_order_detail)) {
            $this->logging == 'orders' && Log::info('found : ', $customer_order_detail->toArray());
        } else {
            $this->logging == 'orders' && Log::info('nothing found! ');
        }

        return $customer_order_detail;
    }

    public function storeOrUpdateCustomerOrderDetail($order, $orderCustomer, $flightTour, $flightType, $addon, $reference)
    {
        $this->logging == 'orders' && Log::info('storeOrUpdateCustomerOrderDetail --- check flightTour', $flightTour->toArray());
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
            if ($customer_order_detail->orders_customer_id === $orderCustomer->id
                && $customer_order_detail->inventory_tour_id === $flightTour->id
                && $customer_order_detail->type === $flightType
                && $customer_order_detail->addon === intval($addon)
                && $customer_order_detail->reference === $reference) {
                    ActionsRepository::log('Accessed Customer Order Detail, nothing to update', $orderCustomer->order_id, $flightTour->flight_inventory_id, $reference);
                    //return;
                }
        }
        try {
            $customer_order_detail->orders_customer_id = $orderCustomer->id;
            $customer_order_detail->inventory_id = $flightTour->flight_inventory_id;
            $customer_order_detail->order_id = $order->id;
            $customer_order_detail->component_type = 'flight';
            $customer_order_detail->type=$flightType;
            $customer_order_detail->inventory_tour_id = $flightTour->id;
            $customer_order_detail->date_time = now();
            $customer_order_detail->status = $status;
            $customer_order_detail->reference = $reference;
            $customer_order_detail->addon = $addon;
            $customer_order_detail->cost = isset($flightTour->sales_price) ? $flightTour->sales_price : 0;
            $actionDescription = 'Customer Order Detail ';
            if ($addon) {
                $actionDescription .= ' addon ';
            } else {
                $actionDescription .= ' group ';
            }
            $actionDescription .= $status;
            ActionsRepository::log($actionDescription, $orderCustomer->order_id, $flightTour->id, $status);
            $customer_order_detail->save();
            $this->logging == 'orders' && Log::info('saving customer_order_detail record', $customer_order_detail->toArray());

            return true;
        } catch(\Exception $e) {
            $this->logging == 'orders' && Log::info('ERROR updating customer order detail'.$e->getMessage());
            return false;
        };
}
    
    /**
     * storeOrUpdateOrderCustomer - stores the orderCustomer data from the booking form
     *
     * @param Customer $customer
     * @param Request $request
     * @param boolean $isLead
     * @return JSON (record saved)
     */
    private function storeOrUpdateOrderCustomer(Customer $customer, Request $request, $isLead = false) 
    {
        if (empty($request->order_id)) {
            throw new \Exception('storeOrUpdateOrderCustomer has no order ID');
        }
        $ordersCustomer = new OrdersCustomer();
        $this->logging == 'orders' && Log::info('loading ordercustomer  order '. $request->order_id.' customer: '.$customer->id);
        $ordersCustomerExists = $ordersCustomer
            ->where('order_id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->first();
        if ($ordersCustomerExists) {
            $ordersCustomer = $ordersCustomerExists;
            $this->logging == 'orders' && Log::info('orderCustomer record', $ordersCustomerExists->toArray());
            $this->updateOrderCustomerFields($ordersCustomer, $request);
            // Log::info('ordercustomer exists, updating');
        } else {
            $ordersCustomer->order_id = $request->order_id;
            $ordersCustomer->customer_id = $customer->id;
            $this->logging == 'orders' && Log::info('creating ordercustomer for order '. $request->order_id.' customer: '.$customer->id);
        }
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
            $this->logging == 'customers' && Log::info('saving customer details ', $customer->toArray());
        }
        $customer->save();

        return $customer;
    }

    /**
     * leadTraveller - save the leadTraveller data
     *
     * @param Request $request
     * @return array of what was saved in customer and orderCustomer
     */
    public function leadTraveller(Request $request) 
    {
        $this->logging == 'customers' && Log::info('leadTraveller', $request->toArray());
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
        $this->logging == 'customers' && Log::info('additionalTraveller', $request->toArray());
        $customer = $this->storeOrUpdateCustomer($request);
        $orderCustomer = $this->storeOrUpdateOrderCustomer($customer, $request, false);

        return json_encode(['success' => true, 'customer' => $customer, 'orderCustomer' => $orderCustomer]);
    }

    public function removeAdditionalTraveller(Request $request)
    {
        $order_customer_id = $request->order_customer_id;
        $orderCustomer = OrdersCustomer::find($order_customer_id);
        $customer = Customer::find($orderCustomer->customer_id);
        $this->logging == 'customers' && Log::info('removing Additional Traveller order_customer_id:' . $order_customer_id);
        $orderCustomer->deleted_at = date('Y-m-d H:i:s');
        $orderCustomer->save();

        return json_encode(['success' => true, 'customer' => $customer]);
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
        $this->logging == 'orders' && Log::info('create order for tour ' . $request->tour);
        $this->logging == 'orders' && Log::info('order id ', $order->toArray());

        return response()->json(["success" => true, "order" => $order]);
    }
}
