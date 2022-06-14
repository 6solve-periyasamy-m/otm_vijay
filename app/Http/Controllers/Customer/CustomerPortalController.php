<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\StaticOrderRepository;

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
        $order = OrderRepository::getFromBookingReference($reference);
        if (!isset($order)) abort(404);
        if ($order->repository->getOrderCustomer($customer) === null) abort(404);
        return $order->repository->getAtolRepository()->showAtolCertificate();
    }


}
