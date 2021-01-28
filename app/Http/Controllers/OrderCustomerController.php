<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdersCustomer;
use App\Models\OrdersAccommodation;
use App\Models\OrdersActivity;
use App\Models\OrdersFlight;
use App\Models\OrdersTransport;
use App\Models\Transport;

class OrderCustomerController extends Controller
{
    public function customerComponents($id)
    {
        return view('customerComponents', [
            'customerOrder' => OrdersCustomer::findOrFail($id),
            'orderAccommodations' => OrdersAccommodation::findByOrderCustomer($id),
            'orderActivities' => OrdersActivity::findByOrderCustomer($id),
            'orderFlights' => OrdersFlight::findByOrderCustomer($id),
            'orderTransports' => OrdersTransport::findByOrderCustomer($id)

            ]);
    }
}
