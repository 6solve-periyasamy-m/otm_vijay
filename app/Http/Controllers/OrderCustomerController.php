<?php

namespace App\Http\Controllers;

use App\Models\OrdersCustomer;
use App\Models\OrdersAccommodation;
use App\Models\OrdersActivity;
use App\Models\OrdersFlight;
use App\Models\OrdersTransport;
use App\Models\Transport;
use App\Repository\OrderRepository;

class OrderCustomerController extends Controller
{
    public function customerComponents($id)
    {
        $customerOrder = OrdersCustomer::findOrFail($id);
        $orderAccommodations = OrdersAccommodation::findByOrderCustomer($id);
        $orderActivities = OrdersActivity::findByOrderCustomer($id);
        $orderFlights = OrdersFlight::findByOrderCustomer($id);
        $orderTransports = OrdersTransport::findByOrderCustomer($id);

        return view('pages.components.customer', [
            'customerOrder' => $customerOrder,
            'orderAccommodations' => $orderAccommodations,
            'orderActivities' => $orderActivities,
            'orderFlights' => $orderFlights,
            'orderTransports' => $orderTransports
        ]);
    }

    public function show($order, OrdersCustomer $orderCustomer) {
        return view('pages.orders.customer', OrderRepository::getOrderCustomerDetails($orderCustomer));
    }
}
