<?php

namespace App\Http\Gateways\Interfaces;

use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;

interface SupportsRedirect
{
    const SUPPORTS_REDIRECT = true;

    public function getRedirect(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): string;
}
