<?php

// Booking Controller: BOOKING FORM Updating API
// TODO: REFACTOR

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Flight;
use App\Models\Booking;

use App\Models\Customer;
// use App\Models\Tour;
use Illuminate\Http\Request;
use App\Models\OrderCustomer;
use App\Models\CustomerOrderDetail;
use Illuminate\Support\Facades\Log;
use App\Repository\ActionsRepository;
use App\Repository\BookingRepository;
use App\Http\Controllers\ApiController;

class BookingController extends ApiController
{
    protected $logging = 'customer';

    /**
     * get
     *
     * @param $token
     * @return void
     */
    public function get($token)
    {
        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->findBookingByToken($token);
        if (isset($booking)) {
            // Log::debug('===>>> booking->customer', [$booking, $booking->customer]);
            return response()->json(['success' => true, 'booking' => $booking, 'tour' => $booking->tour]);
        }
        return response()->json(['success' => false]);
    }

    /**
     * create
     * POST function to create a booking in the repo
     * @param Request $request with parameters:
     * @param string $customer_id
     * @param string $tour_id
     * @param string $token
     * @return void
     */
    public function create(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'tour_id' => 'required',
            'token' => 'required'
        ]);
        $customer_id = $request->customer_id;
        $tour_id = $request->tour_id;
        $token = $request->token;

        $bookingRepo = new BookingRepository();
        $booking = $bookingRepo->create($customer_id, $tour_id, $token);
Log::debug('Booking:Create', [$tour_id, $token]);
        return response()->json(["success" => true, 'booking' => $booking]);
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
        throw new \Exception('Booking:createOrder deprecated call');
    
        $order = new Order();
        $order->quote_id = null;
        $order->tour_id = $request->tour;
        //$order->total_order_value = null;
        $order->internal_notes = 'Created by '.$_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['REQUEST_URI'];
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

    public function getInfo() 
    {
        $cod = new CustomerOrderDetail();
        $orders = $cod->select('orders_customer_id')->groupBy('orders_customer_id')->get();
        $customers = $cod->select('orders_customer_id')->join('orders_customers', 'customer_order_details.orders_customer_id', 'orders_customers.id')
            ->groupBy('orders_customer_id')
            ->get();
        $active = $orders->count();
        $customers = $customers->count();
        return response()->json(["success" => true, "customers" => $customers, "active" => $active]);
    }
}
