<?php

namespace App\Repository\Model\Quote;

use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
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

    public function get(): Model
    {
        return $this->quote;
    }

    public function update(array $data): Model
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
