<?php

namespace App\Http\Controllers;

use App\Models\Customer\Customer;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\Model\Order\OrderRepository;

class CustomerController extends Controller
{
    protected Customer $user;

    public function __construct()
    {
        $this->user = CustomerAuthenticationRepository::getCustomer();
        if (!isset($this->user)) {
            abort(404);
        }
    }

    protected function fetchOrder(string $booking)
    {
        return OrderRepository::getFromBookingReference($booking);
    }
}
