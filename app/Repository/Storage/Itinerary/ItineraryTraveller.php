<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Customer\Customer;

class ItineraryTraveller
{
    public function __construct(
        public Customer|null $customer,
        public bool $paying,
        public bool $travelling,
    )
    {}
}
