<?php

namespace App\Repository\Model\Quote;

use App\Models\Customer\Customer;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteInstallment;
use App\Models\Quote\QuotePricePoint;
use App\Models\Quote\QuoteTraveller;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use Carbon\Carbon;

class QuoteRepository extends ModelRepository
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public static function create(Tour $tour, Customer $customer, array $data = [], array $leadData = []): Quote
    {
        $quote = Quote::create(array_merge(['tour_id' => $tour->id,], $data));
        $lead = $quote->repository->addTraveller($customer, $leadData);
        $quote->lead_traveller_id = $lead->id;
        $quote->reference = $quote->repository->generateReference();
        $quote->repository->save();
        return $quote;
    }

    public function generateReference(): string
    {
        return setting('quote.prefix', 'OTMQ')
            . str_pad($this->quote->tour->id, 4, '0', STR_PAD_LEFT)
            . str_pad($this->quote->id, 4, '0', STR_PAD_LEFT)
            . str_pad($this->quote->leadTraveller->id, 4, '0', STR_PAD_LEFT)
            . substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(4 / strlen($x)))), 1, 4);
    }

    public function addTraveller(Customer $customer, array $data = []): QuoteTraveller
    {
        return QuoteTravellerRepository::create($this->quote, $customer, $data);
    }

    public function addPricePoint(int $customerCount, float $pricePerPerson): QuotePricePoint
    {
        $pricePoint = QuotePricePoint::make([
           'quantity' => $customerCount,
           'price_per_person' => $pricePerPerson,
        ]);
        $this->quote->pricePoints()->save($pricePoint);
        return $pricePoint;
    }

    public function addInstallment(Carbon $due, float $amount): QuoteInstallment
    {
        $installment = QuoteInstallment::make([
            'due_on' => $due,
            'amount' => $amount
        ]);
        $this->quote->installments()->save($installment);
        return $installment;
    }

    public function cloneInstallments()
    {
        foreach ($this->quote->tour->paymentInstallments as $installment) {
            $this->addInstallment($installment->due_on, $installment->amount);
        }
    }

    public function get(): Quote
    {
        return $this->quote;
    }

    public function update(array $data): Quote
    {
        $this->quote->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->quote->save();
    }

    public function delete(): bool
    {
        return $this->quote->delete();
    }

    public function isDeleted(): bool
    {
        return $this->quote->trashed();
    }

    public function __toString(): string
    {
        return $this->quote->reference;
    }

    public function getPricePerPerson(int $count): ?QuotePricePoint
    {
        return QuotePricePoint::where('quote_id', $this->quote->id)->where('quantity', '<=', $count)->orderBy('quantity', 'desc')->first();
    }
}
