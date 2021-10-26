<?php

// Book Flight Details Controller: BOOKING FORM Updating API
// Flight Details (refactored out of BookingController)
// TODO: move model queries to repository
// TODO: refactor private functions

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Repository\ActionsRepository;
use App\Models\Tour;
use App\Models\Order;
use App\Models\OrdersCustomer;
use App\Models\Flight;
use App\Models\FlightInventoryTour;
use App\Models\CustomerOrderDetail;

use App\Repository\CustomerOrderDetailRepository;

class BookFlightDetailsController extends ApiController
{
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

    private function removeCustomerOrderDetail($orderCustomer, $inventory_tour_id, $type, $custom, $reference) {
        $cod = new CustomerOrderDetail();
        $result = $cod->where('orders_customer_id', $orderCustomer->id)
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
            ->join('orders_customers', 'orders_customers.id', 'customer_order_details.orders_customer_id')
            ->join('orders', 'orders_customers.order_id', 'orders.id')
            ->whereNull('customer_order_details.deleted_at')
            ->where('customer_order_details.orders_customer_id', $orders_customer_id)
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
            if ($customer_order_detail->orders_customer_id === $orderCustomer->id
                && $customer_order_detail->inventory_tour_id === $flightTour->id
                && $customer_order_detail->type === $flightType
                && $customer_order_detail->addon === intval($addon)
                && $customer_order_detail->reference === $reference) {
                ActionsRepository::log('Accessed Customer Order Detail, nothing to update', $orderCustomer->order_id, $flightTour->flight_inventory_id, $reference);
                //return;
            }
        }
        $customer_order_detail->orders_customer_id = $orderCustomer->id;
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
         * bookFlightDetails
         * save flight details for a pax tour flight
         * 1. create / update orders_customers
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
            if (!$flightTour) {
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

        if ($rejection || !$customer_id) {
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
        $orderCustomers = new OrdersCustomer();
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
}

