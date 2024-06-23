<?php

namespace App\Http\Controllers;

use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Repository\Authentication\CustomerAuthenticationRepository;
use App\Repository\Model\Order\OrderRepository;

class CustomerController extends Controller
{
    private Customer $iUser;

    protected function fetchOrder(string $booking): ?Order
    {
        return OrderRepository::getFromBookingReference($booking);
    }

    public function user(): Customer|null
    {
        if (!isset($this->iUser)) $this->iUser = CustomerAuthenticationRepository::getCustomer();
        return $this->iUser;
    }
}
