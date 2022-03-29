<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Repository\BookingRepository;
use App\Repository\CustomerAuthenticationRepository;
use App\Repository\OrderRepository;
use Auth;

class CustomerPortalController extends Controller
{
    public function show(Customer $customer)
    {
        return view('pages.customer.portal', ['customer' => CustomerAuthenticationRepository::getCustomer(),]);
    }

    public function showAtol(string $reference)
    {
        $customer = CustomerAuthenticationRepository::getCustomer();
        if (!isset($customer)) abort(404);
        $order = OrderRepository::getOrderFromBookingReference($reference);
        if (!isset($order)) abort(404);
        if (!OrderRepository::isOrderCustomer($order, $customer)) abort(404);
        return OrderRepository::showAtolCertificate($order);
    }


}
