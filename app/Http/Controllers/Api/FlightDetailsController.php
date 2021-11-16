<?php

// Book Flight Details Controller: BOOKING FORM Updating API
// Flight Details (refactored out of BookingController)
// TODO: move model queries to repository
// TODO: refactor private functions

namespace App\Http\Controllers\Api;

use App\Models\Tour;
use App\Models\Order;
use App\Models\Flight;

use Illuminate\Http\Request;
use App\Models\OrderCustomer;
use App\Models\CustomerOrderDetail;
use App\Models\FlightInventoryTour;
use Illuminate\Support\Facades\Log;
use App\Repository\ActionsRepository;
use App\Http\Controllers\ApiController;
use App\Repository\CustomerOrderDetailRepository;

class FlightDetailsController extends ApiController
{
    /**
     * storeOrUpdateFlightBooking TODO
     *
     * @param [type] $orderCustomer
     * @param [type] $flightTour
     * @param [type] $flightType
     * @param [type] $addon
     * @param [type] $reference
     * @return void
     */
    private function storeOrUpdateFlightBooking($orderCustomer, $flightTour, $flightType, $addon, $reference)
    {
        return;
    }
    /**
         * bookFlightDetails  WIP (convert to booking tables)
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
            'booking_id' => 'required',
            'flight_type' => 'required',
            'inventory_tour_id' => 'required',
            'custom' => 'required',
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        /// $order_id = $request->order_id;
        $booking_id = $request->booking_id;
        $flight_type = $request->flight_type;
        $inventory_tour_id = $request->inventory_tour_id;
        $custom = $request->custom;

        // token is posted: why look it up?  opportunity to catch a false post?
        $token = $_COOKIE['OTM_booking_token'];
        if ($token !== $request->token) {
            throw new \Exception('Booking token mismatch');
        }
        $token = $request->token;

        $this->logging == 'flights' && Log::info('***** bookFlightDetails parameters:', [$customer_id, $tour_id, $order_id, $flight_type, $inventory_tour_id, $custom, $token]);

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
            $result = $this->storeOrUpdateFlightBooking($orderCustomer, $flightTour, $flight_type, $custom, $token);
            //$result = $this->storeOrUpdateCustomerOrderDetail($orderCustomer, $flightTour, $flight_type, $custom, $token);
            $this->logging === 'flights' && Log::info('storeOrUpdateCustomerOrderDetail returned!', $result);
        } else {
            $result = $this->removeFlightBooking('flight', $order, $orderCustomer, $flight_type, $custom, $token);
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
}
