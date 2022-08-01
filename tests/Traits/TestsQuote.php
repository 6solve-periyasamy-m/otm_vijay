<?php

namespace Tests\Traits;

use App\Models\Customer\Customer;
use App\Models\Quote\Quote;
use App\Repository\Model\Quote\QuoteRepository;

trait TestsQuote
{
    use TestsTour;

    public function generateQuote(): Quote
    {
        $tour = $this->generateTour();
        $customer = Customer::factory()->create();
        return QuoteRepository::createFromTour($tour, $customer);
    }
}
