<?php

namespace App\Http\Controllers;

use App\Models\OrderCustomer;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderFlight;
use App\Models\OrderTransport;
use App\Models\Transport;
use App\Repository\OrderRepository;

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

    public function show($order, OrderCustomer $orderCustomer) {
        return view('pages.orders.customer', OrderRepository::getOrderCustomerDetails($orderCustomer));
    }
}
