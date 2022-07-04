<?php

namespace App\Repository\Model\Quote;

use App\Models\Customer\Customer;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteProspect;
use App\Models\Quote\QuoteTraveller;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use Illuminate\Database\Eloquent\Model;

class QuoteTravellerRepository extends ModelRepository
{
    private QuoteTraveller $traveller;

    public function __construct(QuoteTraveller $traveller)
    {
        $this->traveller = $traveller;
    }

    public static function create(Quote $quote, ?Customer $customer = null, array $data = []): QuoteTraveller
    {
        $traveller = QuoteTraveller::make($data);
        if (isset($customer)) {
            $prospect = QuoteProspect::create(['customer_id' => $customer->id]);
            $traveller->quote_prospect_id = $prospect->id;
        }
        $quote->travellers()->save($traveller);
        return $traveller;
    }

    /**
     * @param bool $accommodation
     * @param bool $activities
     * @param bool $flights
     * @param bool $transport
     * @param bool $extras
     * @param array $filter
     * @return QuoteComponentRepository[]
     */
    public function getComponents(bool $accommodation = true, bool $activities = true, bool $flights = true, bool $transport = true, bool $extras = true, array $filter = ['Included', 'Upgrade', 'Add-on']): array
    {
        $components = [];
        if ($accommodation) {
            foreach ($this->traveller->accommodation()->with('tourComponent', 'tourComponent.inventory')->get() as $component) {
                if (in_array($component->tourComponent->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($activities) {
            foreach ($this->traveller->activities()->with('tourComponent', 'tourComponent.inventory')->get() as $component) {
                if (in_array($component->tourComponent->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($flights) {
            foreach ($this->traveller->flights()->with('tourComponent', 'tourComponent.inventory')->get() as $component) {
                if (in_array($component->tourComponent->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($transport) {
            foreach ($this->traveller->transport()->with('tourComponent', 'tourComponent.inventory')->get() as $component) {
                if (in_array($component->tourComponent->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($extras) {
            foreach ($this->traveller->merchandise()->with('tourComponent')->get() as $component) {
                if (in_array($component->tourComponent->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        return $components;
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

    public function getPurchaseTotal(): float
    {
        $total = 0;
        foreach ($this->getComponents() as $component) {
            $total += $component->getPurchasePrice();
        }
        return $total;
    }
}
