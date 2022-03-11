<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\CustomerDashboardRepository;
use App\Repository\OrderRepository;

class CustomerTourController extends Controller
{
    public function showItinerary(?string $reference = null)
    {
        $customer = CustomerAuthenticationRepository::getCustomer();
        if (isset($reference)) {
            $order = OrderRepository::getOrderFromBookingReference($reference);
        } else {
            $order = $customer->orders()->orderByDesc('ordered_on')->first();
        }
        if (!isset($order)) abort(404);
        $oCustomer = null;
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) {
                $oCustomer = $orderCustomer;
                break;
            }
        }
        if (!isset($oCustomer)) abort(404);
        return view('pages.customer.itinerary',
            ['itinerary' => CustomerDashboardRepository::generateItinerary($oCustomer), 'order' => $order, 'orders' => CustomerAuthenticationRepository::getCustomer()->orders]);
    }
}
