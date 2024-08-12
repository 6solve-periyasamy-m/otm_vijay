<?php

namespace App\Http\Gateways\Interfaces;

use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Order\Payment\PaymentIntention;

interface SupportsApiKeys
{
    public const SUPPORTS_API_KEYS = true;

    public function getApiKeys(array $items, PaymentIntention $intention, Customer|BookingTraveller $customer, string $success = null): array;
}
