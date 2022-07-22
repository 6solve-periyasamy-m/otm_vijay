<?php

namespace App\Repository\Model\Quote;

use App\Models\Customer\Customer;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteInstallment;
use App\Models\Quote\QuotePricePoint;
use App\Models\Quote\QuoteProspect;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Tour\TourRepository;
use App\Repository\RoomingRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuoteRepository extends ModelRepository
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public static function create(Tour $tour, ?Customer $customer = null, array $data = [], array $leadData = []): Quote
    {
        $quote = Quote::create(array_merge(['tour_id' => $tour->id,], $data));
        $lead = $quote->repository->createProspect($customer, $leadData);
        $quote->lead_traveller_id = $lead->id;
        $quote->reference = $quote->repository->generateReference();
        $quote->repository->save();
        foreach ($tour->repository->getComponents(true, true, true, true, true, ['Included']) as $component) {
            $component->addToQuote($quote);
        }
        return $quote;
    }

    public function convertToTour(int $customerCount = 1): Tour
    {
        $tour = TourRepository::create([
            'is_active' => false,
            'name' => $this->quote->reference,
            'notes' => $this->quote->internal_notes,
            'description' => $this->quote->external_notes,
            'date_from' => $this->quote->date_from,
            'date_to' => $this->quote->date_from,
            'terms' => $this->quote->terms,
            'final_payment' => $this->quote->final_payment,
            'stock_control_active' => false,
            'base_price_per_person' => $this->getPricePerPerson($customerCount),
        ]);
        foreach ($this->getComponents() as $repository) {
            $repository->getInventory()->addToTour($tour, $repository->getTourComponentType(), $repository->getCost());
        }
        foreach ($this->quote->installments as $installment) {
            $tour->repository->addInstallment($installment->due_on, $installment->amount, $installment->percentage);
        }
        return $tour;
    }

    public function generateReference(): string
    {
        return setting('quote.prefix', 'OTMQ')
            . str_pad($this->quote->tour->id, 4, '0', STR_PAD_LEFT)
            . str_pad($this->quote->id, 4, '0', STR_PAD_LEFT)
            . str_pad($this->quote->leadTraveller->id, 4, '0', STR_PAD_LEFT)
            . substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(4 / strlen($x)))), 1, 4);
    }

    public function createProspect(?Customer $customer = null, array $data = []): QuoteProspect
    {
        if (isset($customer)) {
            $prospect = QuoteProspect::create(['customer_id' => $customer->id]);
        } else {
            $prospect = QuoteProspect::create($data);
        }
        return $prospect;
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
            foreach ($this->quote->accommodation()->with('inventory')->get() as $component) {
                if (in_array($component?->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($activities) {
            foreach ($this->quote->activities()->with('inventory')->get() as $component) {
                if (in_array($component?->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($flights) {
            foreach ($this->quote->flights()->with('inventory')->get() as $component) {
                if (in_array($component->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($transport) {
            foreach ($this->quote->transport()->with('inventory')->get() as $component) {
                if (!isset($component)) dd($component);
                if (in_array($component?->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        if ($extras) {
            foreach ($this->quote->merchandise()->with('inventory')->get() as $component) {
                if (!isset($component)) dd($component);
                if (in_array($component?->tour_component_type, $filter)) {
                    $components[] = $component->repository;
                }
            }
        }
        return $components;
    }

    public function getPurchaseTotal(): float
    {
        $total = 0;
        foreach ($this->getComponents() as $component) {
            $total += $component->getPurchasePrice();
        }
        return $total;
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
