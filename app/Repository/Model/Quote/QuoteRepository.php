<?php

namespace App\Repository\Model\Quote;

use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Quote\Component\QuoteAccommodation;
use App\Models\Quote\Component\QuoteActivity;
use App\Models\Quote\Component\QuoteFlight;
use App\Models\Quote\Component\QuoteMerchandise;
use App\Models\Quote\Component\QuoteTransport;
use App\Models\Quote\Quote;
use App\Models\Quote\QuoteInstallment;
use App\Models\Quote\QuotePricePoint;
use App\Models\Quote\QuoteProspect;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\Model\Tour\TourRepository;
use App\Repository\RoomingRepository;
use App\Repository\Storage\ConvertedCustomer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuoteRepository extends ModelRepository
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public static function createFromTour(Tour $tour, ?Customer $customer = null, array $data = [], array $leadData = []): Quote
    {
        $quote = Quote::create([
            'tour_id' => $tour->id,
            'event_id' => $tour->event_id,
            'deposit' => $tour->deposit,
            'final_payment' => $tour->final_payment,
            'date_from' => $tour->date_from,
            'date_to' => $tour->date_to,
            'terms' => $tour->terms,
            'invoice_footer' => $tour->invoice_footer,
            'name' => $tour->name,
            'description' => $tour->description,
            ...$data,
        ]);
        $lead = $quote->repository->createProspect($customer, $leadData);
        $quote->lead_traveller_id = $lead->id;
        $quote->reference = $quote->repository->generateReference();
        $quote->repository->save();
        foreach ($tour->repository->getComponents(true, true, true, true, true, ['Included']) as $component) {
            $component->addToQuote($quote);
        }
        $quote->repository->cloneInstallments();
        $quote->repository->addPricePoint(1, $tour->base_price_per_person);
        return $quote;
    }

    public static function createBespoke(?Customer $customer = null, float $pricePerPerson = 0, array $data = [], array $leadData = []): Quote
    {
        $quote = Quote::create($data);
        $lead = $quote->repository->createProspect($customer, $leadData);
        $quote->lead_traveller_id = $lead->id;
        $quote->reference = $quote->repository->generateReference();
        $quote->repository->save();
        $quote->repository->addPricePoint(1, $pricePerPerson);
        return $quote;
    }

    public static function getFromReference(string $reference): ?Quote
    {
        return Quote::where('reference', '=', $reference)->first();
    }

    /**
     * @param ConvertedCustomer $lead
     * @param ConvertedCustomer[] $travellers
     * @return Order
     */
    public function convertToOrder(ConvertedCustomer $lead, array $travellers = []): Order
    {
        $paying = $lead->paying ? 1 : 0;
        foreach ($travellers as $traveller) { $paying += $traveller->paying ? 1 : 0; }
        $pricePerPerson = $this->getPricePerPerson($paying)->price_per_person;
        $lead->data['tour_cost'] = $pricePerPerson;
        $lead->data['single_occupancy_surcharge'] = $this->quote->single_occupancy_surcharge;
        foreach ($travellers as $traveller) {
            $traveller->data['tour_cost'] = $pricePerPerson;
            $traveller->data['single_occupancy_surcharge'] = $this->quote->single_occupancy_surcharge;
        }
        $tour = $this->quote->tour ?? $this->convertToTour($paying);
        $data = [
            'deposit' => $this->quote->deposit,
            'ordered_on' => now(),
            'internal_notes' => $this->quote->internal_notes,
            'external_notes' => $this->quote->external_notes,
            'invoice_footer' => $this->quote->invoice_footer,
        ];
        return OrderRepository::create($tour, $data, $lead, $travellers);
    }

    public function convertToTour(int $customerCount = 1): Tour
    {
        $tour = TourRepository::create([
            'is_active' => false,
            'name' => $this->quote->name,
            'notes' => $this->quote->internal_notes,
            'description' => $this->quote->description,
            'date_from' => $this->quote->date_from,
            'date_to' => $this->quote->date_from,
            'terms' => $this->quote->terms,
            'final_payment' => $this->quote->final_payment,
            'stock_control_active' => false,
            'base_price_per_person' => $this->getPricePerPerson($customerCount),
            'single_occupancy_surcharge' => $this->quote->single_occupancy_surcharge,
        ]);
        foreach ($this->getComponents() as $repository) {
            $repository->getInventory()->addToTour($tour, $repository->getTourComponentType(), $repository->getCost());
        }
        foreach ($this->quote->installments as $installment) {
            $tour->repository->addInstallment($installment->due_on, $installment->amount, $installment->percentage);
        }
        $this->update(['tour_id' => $tour->id, 'locked' => true,]);
        $this->save();
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

    public function addInstallment(Carbon $due, float $amount, bool $percentage = false): QuoteInstallment
    {
        $installment = QuoteInstallment::make([
            'due_on' => $due,
            'amount' => $amount,
            'percentage' => $percentage,
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

    public function getTotalCost(int $paying): float
    {
        return $this->getPricePerPerson($paying)->price_per_person * $paying;
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

    public function updateLead(array $data): QuoteProspect
    {
        $lead = $this->quote->leadTraveller;
        $lead->update($data);
        $lead->save();
        return $lead;
    }

    public function getPurchaseTotal(): float
    {
        return $this->getAccommodationCost() + $this->getActivityCost() + $this->getFlightCost() + $this->getTransportCost() + $this->getMerchandiseCost();
    }

    /**
     * @return Collection|QuoteAccommodation[]
     */
    public function getTemplates(): Collection|array
    {
        return $this->quote->accommodation()->where('is_template', '=', true)->with('inventory')->get();
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

    public function autoAssignTemplating(): void
    {
        $dates = [];
        foreach ($this->quote->accommodation as $quoteComponent) {
            if ($quoteComponent->tour_component_type !== 'Included') continue;
            $start = $quoteComponent->inventory->check_in->clone();
            $start->setTime(0, 0);
            if (array_key_exists($start->unix(), $dates)) {
                if ($quoteComponent->is_template && !$dates[$start->unix()]->is_template) {
                    $dates[$start->unix()] = $quoteComponent;
                }
            } else {
                $dates[$start->unix()] = $quoteComponent;
            }
        }
        foreach ($dates as $quoteComponent) {
            $quoteComponent->is_template = true;
            $quoteComponent->save();
        }
    }
    
    public function getAccommodationCost(): float
    {
        $cost = 0;
        foreach ($this->getTemplates() as $template) {
            $cost += $template->purchase_price ?? 0;
        }
        return $cost;
    }
    
    public function getActivityCost(): float
    {
        $cost = 0;
        /** @var QuoteActivity $component */
        foreach ($this->quote->activities()->with('inventory')->get() as $component) {
            $cost += $component->inventory->purchase_price ?? 0;
        }
        return $cost;
    }
    
    public function getFlightCost(): float
    {
        $cost = 0;
        /** @var QuoteFlight $component */
        foreach ($this->quote->flights()->with('inventory')->get() as $component) {
            $cost += $component->inventory->purchase_price ?? 0;
        }
        return $cost;
    }
    
    public function getTransportCost(): float
    {
        $cost = 0;
        /** @var QuoteTransport $component */
        foreach ($this->quote->transport()->with('inventory')->get() as $component) {
            $cost += $component->inventory->purchase_price ?? 0;
        }
        return $cost;
    }
    
    public function getMerchandiseCost(): float
    {
        $cost = 0;
        /** @var QuoteMerchandise $component */
        foreach ($this->quote->merchandise()->with('inventory')->get() as $component) {
            $cost += $component->inventory->purchase_price ?? 0;
        }
        return $cost;
    }

    public function getResponseStream(int $paying, int $travelling): StreamedResponse
    {
        $invoice = Browsershot::html(view('pdf.quotes.columns', ['quote' => $this->quote, 'paying' => $paying, 'travelling' => $travelling,])->render());
        $invoice->showBackground()->margins(10, 2, 10, 2);
        return response()->stream(function () use ($invoice) { echo $invoice->pdf(); }, 200, ['Content-Type' => 'application/pdf']);
    }

    public function getRemaining(int $paying = 1): float
    {
        $cost = $this->getTotalCost(1);
        $cost -= $this->quote->deposit;
        foreach ($this->quote->installments as $installment) {
            $cost -= $installment->amount;
        }
        return $cost * $paying;
    }

    /**
     * @return QuoteComponentRepository[]
     */
    public function getAccommodationForInvoice(bool $sort = true): array
    {
        $data = [];
        foreach ($this->getTemplates() as $template) {
            $time = $template->repository->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $template->repository;
        }
        if ($sort) ksort($data);
        return $data;
    }

    /**
     * @return QuoteComponentRepository[]
     */
    public function getActivitiesForInvoice(bool $sort = true): array
    {
        $data = [];
        foreach ($this->quote->activities()->with('inventory')->get() as $template) {
            $time = $template->repository->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $template->repository;
        }
        if ($sort) ksort($data);
        return $data;
    }

    /**
     * @return QuoteComponentRepository[]
     */
    public function getFlightsForInvoice(bool $sort = true): array
    {
        $data = [];
        foreach ($this->quote->flights()->with('inventory')->get() as $template) {
            $time = $template->repository->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $template->repository;
        }
        if ($sort) ksort($data);
        return $data;
    }

    /**
     * @return QuoteComponentRepository[]
     */
    public function getTransportForInvoice(bool $sort = true): array
    {
        $data = [];
        foreach ($this->quote->transport()->with('inventory')->get() as $template) {
            $time = $template->repository->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $template->repository;
        }
        if ($sort) ksort($data);
        return $data;
    }

    /**
     * @return QuoteComponentRepository[]
     */
    public function getItinerary(): array
    {
        $data = [];
        foreach ($this->getAccommodationForInvoice(false) as $component) {
            $time = $component->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $component;
        }
        foreach ($this->getActivitiesForInvoice(false) as $component) {
            $time = $component->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $component;
        }
        foreach ($this->getFlightsForInvoice(false) as $component) {
            $time = $component->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $component;
        }
        foreach ($this->getTransportForInvoice(false) as $component) {
            $time = $component->getInventory()->getStartTime()->unix();
            do {
                $exists = array_key_exists($time, $data);
                if ($exists) $time++;
            } while ($exists);
            $data[$time] = $component;
        }
        ksort($data);
        return $data;
    }
}
