<?php

namespace App\Repository\Model\Quote;

use App\Models\Quote\Quote;
use App\Models\Quote\QuoteInstallment;
use App\Models\Quote\QuotePricePoint;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuoteRepository extends ModelRepository
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
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
}
