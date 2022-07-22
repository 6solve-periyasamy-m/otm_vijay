<?php

namespace App\Http\Controllers;

use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;

class OrderCustomerController extends Controller
{
    public function customerComponents($id)
    {
        $customerOrder = OrderCustomer::findOrFail($id);
        $orderAccommodations = OrderAccommodation::findByOrderCustomer($id);
        $orderActivities = OrderActivity::findByOrderCustomer($id);
        $orderFlights = OrderFlight::findByOrderCustomer($id);
        $orderTransports = OrderTransport::findByOrderCustomer($id);

        return view('pages.components.customer', [
            'customerOrder' => $customerOrder,
            'orderAccommodations' => $orderAccommodations,
            'orderActivities' => $orderActivities,
            'orderFlights' => $orderFlights,
            'orderTransports' => $orderTransports
        ]);
    }

    public function show(Order $order, OrderCustomer $orderCustomer) {
        $order->repository->refresh();
        return view('pages.orders.customer', ['orderCustomer' => $orderCustomer,]);
    }
}
