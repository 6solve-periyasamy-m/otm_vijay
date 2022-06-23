<?php

namespace App\Repository\Model\Quote;

use App\Models\Quote\QuoteTraveller;
use App\Repository\Abstracts\ModelRepository;
use Illuminate\Database\Eloquent\Model;

class QuoteTravellerRepository extends ModelRepository
{
    private QuoteTraveller $traveller;

    public function __construct(QuoteTraveller $traveller)
    {
        $this->traveller = $traveller;
    }

    public function isDefault(): bool
    {
        return $this->traveller->quote->default_traveller_id == $this->traveller->id;
    }

    public function get(): Model
    {
        return $this->traveller;
    }

    public function update(array $data): Model
    {
        $this->traveller->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->traveller->save();
    }

    public function delete(): bool
    {
        return $this->traveller->delete();
    }

    public function isDeleted(): bool
    {
        return $this->traveller->trashed();
    }

    public function __toString(): string
    {
        if ($this->traveller?->prospect == null) {
            $string = 'Unspecified Traveller';
        } else {
            $source = $this->traveller->prospect->customer == null ? $this->traveller->prospect : $this->traveller->prospect->customer;
            $string = "{$source->first_name} {$source->last_name}";
        }
        if ($this->isDefault()) $string .= ' (Default)';
        return $string;
    }
}
