<?php

namespace App\Repository\Storage\Quote;

use App\Models\AdditionalCost;
use App\Models\Customer\Customer;
use App\Models\Quote\Quote;
use App\Models\Tour\Tour;
use App\Repository\Model\Quote\QuoteRepository;
use Livewire\Wireable;
use Settings;

class BasicQuote implements Wireable
{

    /** @var array<array{name: string, per_customer: boolean, amount: float}> */

    public function __construct(
        public Tour $tour,
        public int|null $brand = null,
        public string|null $expiry = null,
        public string|null $final = null,
        public int|null $lead = null,
        public bool $travelling = true,
        public bool $paying = true,
        public float|null $singleOccupancy = null,
        public int|null $tax = null,
        public float|null $deposit = null,
        public bool|null $depositPercentage = null,
        public int|null $currency = null,
        public int|null $organization = null,
        public float|null $commission = null,
        public int|null $agent = null,
        public string|null $internalNotes = null,
        public string|null $externalNotes = null,
        public array $costs = [],
    )
    {
        if ($this->final === null) {
            $this->final = $this->tour->final_payment->format('Y-m-d');
        }
        if ($this->expiry === null) {
            $this->expiry = Settings::defaultQuoteExpiry();
        }
        if ($this->deposit === null) {
            $this->deposit = $this->tour->deposit;
        }
        if ($this->depositPercentage === null) {
            $this->depositPercentage = $this->tour->is_deposit_percentage;
        }
        if ($this->brand === null) {
            $this->brand = $this->tour->brand_id;
        }
        if ($this->tax === null) {
            $this->tax = $this->tour->tax_bracket_id;
        }
        if ($this->singleOccupancy === null) {
            $this->singleOccupancy = $this->tour->single_occupancy_surcharge;
        }
        if (empty($this->costs)) {
            foreach ($this->tour->costs as $cost) {
                $this->costs[] = [
                    'name' => $cost->name,
                    'amount' => $cost->amount,
                    'per_customer' => $cost->per_customer,
                ];
            }
        }
    }

    public function convert(): Quote
    {
        $quote = QuoteRepository::createFromTour($this->tour, Customer::find($this->lead), $this->getQuoteDataset(), $this->getCustomerDataset());
        $quote->costs()->delete();
        foreach ($this->costs as $cost) {
            $model = new AdditionalCost([
                'name' => $cost['name'],
                'amount' => $cost['amount'],
                'per_customer' => $cost['per_customer'],
            ]);
            $quote->costs()->save($model);
        }
        return $quote;
    }

    private function getQuoteDataset(): array
    {
        return [
            'expires' => $this->expiry,
            'organization_id' => $this->organization,
            'single_occupancy_surcharge' => $this->singleOccupancy ?? 0.0,
            'internal_notes' => $this->internalNotes,
            'external_notes' => $this->externalNotes,
            'brand_id' => $this->brand,
            'tax_bracket_id' => $this->tax,
            'agent_id' => $this->agent,
            'commission' => $this->commission,
            'currency_id' => $this->currency,
            'final_payment' => $this->final,
            'is_deposit_percentage' => $this->depositPercentage,
            'deposit' => $this->deposit,
        ];
    }

    private function getCustomerDataset(): array
    {
        return [
            'travelling' => $this->travelling,
            'paying' => $this->paying,
        ];
    }

    public function toLivewire(): array
    {
        return [
            'tour' => $this->tour->id,
            'brand' => $this->brand,
            'expiry' => $this->expiry,
            'final' => $this->final,
            'lead' => $this->lead,
            'travelling' => $this->travelling,
            'paying' => $this->paying,
            'singleOccupancy' => $this->singleOccupancy,
            'tax' => $this->tax,
            'deposit' => $this->deposit,
            'depositPercentage' => $this->depositPercentage,
            'currency' => $this->currency,
            'organization' => $this->organization,
            'commission' => $this->commission,
            'agent' => $this->agent,
            'internalNotes' => $this->internalNotes,
            'externalNotes' => $this->externalNotes,
            'costs' => $this->costs,
        ];
    }

    public static function fromLivewire($value): BasicQuote
    {
        $tour = Tour::find($value['tour']);
        return new BasicQuote(
            $tour,
            $value['brand'],
            $value['expiry'],
            $value['final'],
            $value['lead'],
            $value['travelling'],
            $value['paying'],
            $value['singleOccupancy'],
            $value['tax'],
            $value['deposit'],
            $value['depositPercentage'],
            $value['currency'],
            $value['organization'],
            $value['commission'],
            $value['agent'],
            $value['internalNotes'],
            $value['externalNotes'],
            $value['costs'],
        );
    }
}