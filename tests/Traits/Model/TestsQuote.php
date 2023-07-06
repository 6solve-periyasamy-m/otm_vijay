<?php

namespace Tests\Traits\Model;

use App\Models\Customer\Customer;
use App\Models\Quote\Quote;

trait TestsQuote
{
    use TestsTour;

    public function generateQuote(): Quote
    {
        $customer = Customer::factory()->create();
        $quote = Quote::factory()->create();
        $lead = $quote->repository->createProspect($customer);
        $quote->lead_traveller_id = $lead->id;
        $quote->save();
        return $quote;
    }
}
