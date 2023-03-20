<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\CustomerController;
use App\Repository\Model\Order\OrderRepository;

class CustomerPortalController extends CustomerController
{
    public function show()
    {
        return view('pages.customer.portal', ['customer' => $this->user,]);
    }

    public function showAtol(string $reference)
    {
        $order = OrderRepository::getFromBookingReference($reference);
        if (!isset($order)) abort(404);
        if ($order->repository->getOrderCustomer($this->user) === null) abort(404);
        return $order->repository->getAtolRepository()->showAtolCertificate();
    }


}
