<?php

namespace App\Repository\Model\Quote;

use App\Mail\TemplatedMailable;
use App\Models\Customer\Customer;
use App\Models\Helper\QuoteStatus;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
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
use App\Models\Quote\QuoteSection;
use App\Models\Quote\SentQuote;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ComponentPackageRepository;
use App\Repository\Abstracts\QuoteComponentRepository;
use App\Repository\Interfaces\SerializesToJson;
use App\Repository\Model\Order\OrderRepository;
use App\Repository\Model\Tour\TourRepository;
use App\Repository\Storage\ConvertedCustomer;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Log;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuoteRepository extends ComponentPackageRepository implements SerializesToJson
{
    private Quote $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public static function createFromTour(Tour $tour, ?Customer $customer = null, array $data = [], array $leadData = []): Quote
    {
        $quote = Quote::create([
            'event_id' => $tour->event_id,
            'deposit' => $tour->deposit,
            'final_payment' => $tour->final_payment,
            'date_from' => $tour->date_from,
            'date_to' => $tour->date_to,
            'terms' => $tour->terms,
            'invoice_footer' => $tour->invoice_footer ?? "",
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
        $quote->repository->cloneInstallments($tour);
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
        $tour = $this->convertToTour($paying);
        $data = [
            'deposit' => $this->quote->deposit,
            'ordered_on' => now(),
            'internal_notes' => $this->quote->internal_notes,
            'external_notes' => $this->quote->external_notes,
            'invoice_footer' => $this->quote->invoice_footer,
        ];
        $order = OrderRepository::create($tour, $data, $lead, $travellers);
        $this->update(['quote_status' => QuoteStatus::CONVERTED->value, 'order_id' => $order->id]);
        return $order;
    }

    public function getRemainingInstallment(int $paying = 1)
    {
        $price = $this->getPricePerPerson($paying)?->price_per_person ?? 0;
        $total = $this->quote->installments()->sum('amount') + $this->quote->deposit;
        return $price - $total;
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
            $repository->convertToTourComponent($tour);
        }
        foreach ($this->quote->installments as $installment) {
            $tour->repository->addInstallment($installment->due_on, $installment->amount, $installment->percentage);
        }
        $this->save();
        $tour->repository->autoAssignTemplating();
        return $tour;
    }

    public function generateReference(): string
    {
        return setting('quote.prefix', 'OTMQ')
            . str_pad($this->quote->tour?->id ?? 0, 4, '0', STR_PAD_LEFT)
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

    public function hasComponents(): bool
    {
        $components =
            sizeof($this->quote->accommodation) +
            sizeof($this->quote->activities) +
            sizeof($this->quote->flights) +
            sizeof($this->quote->transport);
        return $components > 0;
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

    public function cloneInstallments(Tour $tour)
    {
        foreach ($tour->paymentInstallments as $installment) {
            $this->addInstallment($installment->due_on, $installment->cost);
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
     * @param bool $merchandise
     * @param array $filter
     * @return QuoteComponentRepository[]
     */
    public function getComponents(bool $accommodation = true, bool $activities = true, bool $flights = true, bool $transport = true, bool $merchandise = true, array $filter = ['Included', 'Upgrade', 'Add-on']): array
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
        if ($merchandise) {
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
    public function getTemplates(bool $sql = true): Collection|array
    {
        if ($sql) {
            return $this->quote->accommodation()->where('is_template', '=', true)->with('inventory')->get();
        } else {
            $array = [];
            foreach ($this->quote->accommodation as $accommodation) {
                if ($accommodation->is_template) $array[] = $accommodation;
            }
            return $array;
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
        $price = QuotePricePoint::where('quote_id', $this->quote->id)->where('quantity', '<=', $count)->orderBy('quantity', 'desc')->first();
        if (!isset($price) && $count === 0) {
            return QuotePricePoint::make([
                'quantity' => 0,
                'quote_id' => $this->quote->id,
                'price_per_person' => 0,
            ]);
        } elseif (!isset($price)) {
            $highest = null;
            foreach ($this->quote->pricePoints as $pricePoint) {
                if ($pricePoint->quantity == $count) return $pricePoint;
                if ($pricePoint->quantity < $count && ($highest == null || $highest->quantity < $pricePoint->quantity)) {
                    $highest = $pricePoint;
                }
            }
            $price = $highest;
        }
        return $price;
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

    public function getResponseStream(SentQuote $sent): StreamedResponse
    {
        return response()->stream(function () use ($sent) { echo $this->getStream($sent); }, 200, ['Content-Type' => 'application/pdf']);
    }

    public function getStream(SentQuote $sent): string
    {
        $invoice = Browsershot::html(view('pdf.quotes.columns', ['sent' => $sent,])->render());
        $invoice->showBackground()->margins(10, 2, 10, 2);
        return $invoice->pdf();
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
        foreach ($this->getTemplates(false) as $template) {
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
        foreach ($this->quote->activities as $template) {
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
        foreach ($this->quote->flights as $template) {
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
        foreach ($this->quote->transport as $template) {
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

    public function serialize(): string
    {
        $quote = $this->quote->makeHidden('id', 'lead_traveller_id', 'order_id', 'created_at', 'updated_at', 'deleted_at')->toArray();
        $quote['lead'] = ['customer_id' => $this->quote->leadTraveller->customer_id, 'travelling' => $this->quote->leadTraveller->travelling, 'paying' => $this->quote->leadTraveller->paying,];
        $components = ['accommodation' => [], 'activity' => [], 'flight' => [], 'transport' => [], 'merchandise' => []];
        foreach ($this->getComponents() as $component) {
            $data = [
                'inventory' => $component->getInventory()->get()->id,
                'tour_component_type' => $component->getTourComponentType(),
                'tour_sales_price' => $component->get()->tour_sales_price,
                'price_shown' => $component->priceShown(),
            ];
            switch ($component->getComponentType()) {
                case 'accommodation':
                    $data['is_template'] = $component->get()->is_template;
                    break;
                case 'flight':
                    $data['flight_type'] = $component->get()->flight_type;
                    break;
            }
            $components[$component->getComponentType()][] = $data;
        }
        $quote = array_merge($quote, $components);
        $installments = [];
        $pricepoints = [];
        $sections = [];
        foreach ($this->quote->installments as $installment) { $installments[] = ['due_on' => $installment->due_on->format('Y-m-d'), 'amount' => $installment->amount,]; }
        foreach ($this->quote->pricePoints as $pricePoint) { $pricepoints[$pricePoint->quantity] = $pricePoint->price_per_person; }
        foreach ($this->quote->sections as $section) { $sections[] =  $section->serialize(); }
        $quote['installments'] = $installments;
        $quote['pricepoints'] = $pricepoints;
        $quote['sections'] = $sections;
        return json_encode($quote);
    }

    public static function deserialize(array $data): Quote
    {
        $lead = QuoteProspect::make($data['lead']);
        unset($data['lead']);
        $accommodation = [];
        foreach ($data['accommodation'] as $datum) {
            $accommodation[] = QuoteAccommodation::make([
                'accommodation_inventory_id' => $datum['inventory'],
                'tour_component_type' => $datum['tour_component_type'],
                'tour_sales_price' => $datum['tour_sales_price'],
                'is_template' => $datum['is_template'],
                'price_shown' => $datum['price_shown'] ?? false,
            ]);
        }
        $activity = [];
        foreach ($data['activity'] as $datum) {
            $activity[] = QuoteActivity::make([
                'activity_inventory_id' => $datum['inventory'],
                'tour_component_type' => $datum['tour_component_type'],
                'tour_sales_price' => $datum['tour_sales_price'],
                'price_shown' => $datum['price_shown'] ?? false,
            ]);
        }
        $flight = [];
        foreach ($data['flight'] as $datum) {
            $flight[] = QuoteFlight::make([
                'flight_inventory_id' => $datum['inventory'],
                'tour_component_type' => $datum['tour_component_type'],
                'tour_sales_price' => $datum['tour_sales_price'],
                'flight_type' => $datum['flight_type'],
                'price_shown' => $datum['price_shown'] ?? false,
            ]);
        }
        $transport = [];
        foreach ($data['transport'] as $datum) {
            $transport[] = QuoteTransport::make([
                'transport_inventory_id' => $datum['inventory'],
                'tour_component_type' => $datum['tour_component_type'],
                'tour_sales_price' => $datum['tour_sales_price'],
                'price_shown' => $datum['price_shown'] ?? false,
            ]);
        }
        $merchandise = [];
        foreach ($data['merchandise'] as $datum) {
            $merchandise[] = QuoteMerchandise::make([
                'merchandise_inventory_id' => $datum['inventory'],
                'tour_component_type' => $datum['tour_component_type'],
                'tour_sales_price' => $datum['tour_sales_price'],
                'price_shown' => $datum['price_shown'] ?? false,
            ]);
        }
        unset($data['accommodation']);
        unset($data['activity']);
        unset($data['flight']);
        unset($data['transport']);
        unset($data['merchandise']);
        $installments = [];
        foreach ($data['installments'] as $installment) {
            $installments[] = QuoteInstallment::make(['due_on' => Carbon::parse($installment['due_on']), 'amount' => $installment['amount']]);
        }
        unset($data['installments']);
        $pricepoints = [];
        foreach ($data['pricepoints'] as $quantity => $price) {
            $pricepoints[] = QuotePricePoint::make(['quantity' => $quantity, 'price_per_person' => $price]);
        }
        unset($data['pricepoints']);
        $sections = [];
        foreach ($data['sections'] ?? [] as $datum) {
            $sections[] = QuoteSection::make($datum);
        }
        unset($data['sections']);
        unset($data['lead_traveller']);
        $quote = Quote::make($data);
        $quote->setRelations([
            'pricePoints' => $pricepoints,
            'installments' => $installments,
            'accommodation' => $accommodation,
            'activities' => $activity,
            'flights' => $flight,
            'transport' => $transport,
            'merchandise' => $merchandise,
            'sections' => $sections,
            'leadTraveller' => $lead,
        ]);
        return $quote;
    }

    public function makeSent(string $email, int $paying, int $travelling): ?SentQuote
    {
        return SentQuote::make([
            'quote_id' => $this->quote->id,
            'sent' => now(),
            'recipient' => $email,
            'travelling' => $travelling,
            'paying' => $paying,
            'data' => $this->serialize(),
        ]);
    }

    public function generateSent(string $email, int $paying, int $travelling): ?SentQuote
    {
        $sent = $this->makeSent($email, $paying, $travelling);
        $sent->save();
        $this->quote->repository->update(['revision' => $this->quote->revision + 1, 'quote_status' => QuoteStatus::AWAITING->value]);
        return $sent;
    }

    public function resend(SentQuote $sent, string $email = null): void
    {
        $mailable = new TemplatedMailable(setting('email.quote.subject', 'Template Quote'), setting('email.quote.template', 'Template Quote Body'));
        try {
            $mail = Mail::to($email);
            if (config('mail.bcc') !== null) { $mail->bcc(config('mail.bcc')); }
            $mailable->attachData($this->getStream($sent), $this->quote->reference . '.pdf', ['mime' => 'application/pdf',]);
            $mail->send($mailable);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public static function deserializeAndSave(SentQuote $sent): Quote
    {
        $quote = QuoteRepository::deserialize(json_decode($sent->data, true));
        $accommodation = $quote->accommodation;
        $activities = $quote->activities;
        $flights = $quote->flights;
        $transport = $quote->transport;
        $installments = $quote->installments;
        $pricepoints = $quote->pricePoints;
        $lead = $quote->leadTraveller;
        $sections = $quote->sections;
        $lead->save();
        $quote->internal_notes .= "\nRebuilt from Quote sent at " . f_datetime($sent->sent);
        $quote->lead_traveller_id = $lead->id;
        $quote->revision += 1;
        $quote->save();
        $quote->accommodation()->saveMany($accommodation);
        $quote->activities()->saveMany($activities);
        $quote->flights()->saveMany($flights);
        $quote->transport()->saveMany($transport);
        $quote->installments()->saveMany($installments);
        $quote->pricePoints()->saveMany($pricepoints);
        $quote->sentQuotes()->saveMany($sent->quote->sentQuotes);
        $quote->sections()->saveMany($sections);
        return $quote;
    }

    public function generateGenericCustomer(bool $paying): Customer
    {
        $homeAddress = Address::create([
            'name' => 'Generic Customer Address',
            'address_parent_id' => AddressParent::getParentId('customer'),
        ]);
        $billingAddress = $homeAddress->repository->cloneToNew(AddressParent::getParentId('customer'));
        return Customer::create([
            'first_name' => "Unknown " . ($paying ? "Paying" : "Non-Paying") . " Traveller",
            'last_name' => $this->quote->reference,
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            'date_of_birth' => now(),
        ]);
    }

    public function hasSections(): bool
    {
        foreach ($this->quote->sections as $section) {
            if (!$section->hidden) return true;
        }
        return false;
    }

    public function getCustomerCostToCompany(): float
    {
        $cost = $this->quote->repository->getPurchaseTotal();
        foreach ($this->quote->costs()->where('per_customer', true)->get() as $additional) {
            $cost += $additional->amount;
        }
        return $cost;
    }

    public function getTotalCostToCompany(int $travellers = 1): float
    {
        $cost = $this->getCustomerCostToCompany() * $travellers;
        foreach ($this->quote->costs()->where('per_customer', false)->get() as $additional) {
            $cost += $additional->amount;
        }
        return $cost;
    }

    public function forceDelete()
    {
        $this->quote->leadTraveller()->forceDelete();
        $this->quote->accommodation()->forceDelete();
        $this->quote->activities()->forceDelete();
        $this->quote->flights()->forceDelete();
        $this->quote->transport()->forceDelete();
        $this->quote->merchandise()->forceDelete();
        $this->quote->sentQuotes()->forceDelete();
        $this->quote->sections()->forceDelete();
        $this->quote->pricePoints()->forceDelete();
        $this->quote->installments()->forceDelete();
        $this->quote->costs()->forceDelete();
        $this->quote->forceDelete();
    }
}
