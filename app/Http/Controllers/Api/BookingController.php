<?php

// Booking Controller: BOOKING FORM Updating API
// TODO: REFACTOR

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Repository\ActionsRepository;
// use App\Models\Tour;
use App\Models\Order;
use App\Models\CustomerOrderDetail;


class BookingController extends ApiController
{
    protected $logging = 'customer';

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
