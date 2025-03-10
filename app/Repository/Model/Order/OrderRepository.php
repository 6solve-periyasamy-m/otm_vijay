<?php

namespace App\Repository\Model\Order;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Exceptions\MailDisabledException;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\ActivityCategory;
use App\Models\Helper\Enum\AddressParent;
use App\Models\Helper\Enum\OrderStatus;
use App\Models\Location\Address;
use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCache;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use App\Models\Order\Payment\PaymentReminder;
use App\Models\System\FellohLink;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\GeneratesFellohData;
use App\Repository\Mailing\Mailer\Order\OrderMailer;
use App\Repository\Model\Accommodation\AccommodationInventoryRepository;
use App\Repository\RoomingRepository;
use App\Repository\Storage\ConvertedCustomer;
use App\Repository\Storage\Itinerary\Itinerary;
use App\Repository\Storage\Itinerary\ItineraryItem;
use App\Repository\Storage\Itinerary\ItineraryPayment;
use App\Repository\Storage\Itinerary\ItineraryPaymentDetails;
use App\Repository\Storage\Itinerary\ItinerarySchedule;
use App\Repository\Storage\Itinerary\ItineraryScheduleType;
use App\Repository\Storage\Itinerary\ItineraryTraveller;
use App\Repository\Storage\Order\MergedAccommodation;
use App\Repository\Storage\Rooming\AccommodationByDateStorage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository extends ModelRepository implements GeneratesFellohData
{
    private Order $order;
    private AtolRepository $atolRepository;
    private float|null $cost;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->atolRepository = new AtolRepository($order);
        $this->cost = $this->order->cache?->cost;
    }

    public function get(): Order
    {
        return $this->order;
    }

    public function save(): bool
    {
        $this->refresh();
        return $this->order->save();
    }

    public function update(array $data): Order
    {
        $this->order->update($data);
        $this->save();
        return $this->get();
    }

    public function delete(): bool
    {
        return $this->order->delete();
    }

    public function isDeleted(): bool
    {
        return $this->order->trashed();
    }

    public function __toString(): string
    {
        return "Order {$this->order->booking_reference}: {$this->order->tour->name} ({$this->order->lead_booker_name})";
    }

    public static function find($id): Order|null
    {
        return Order::find($id);
    }

    /**
     * Get an instance of OrderMailer for the current Order
     * @param bool $force Should sending be forced
     * @return OrderMailer
     */
    public function mailer(bool $force = false): OrderMailer
    {
        return new OrderMailer($this->order, $force);
    }

    /**
     * Get a sum of the base cost of all paying customers
     * @return float|int
     */
    public function getTravellerBaseCosts(): float|int
    {
        return $this->order->orderCustomers()->where('is_charged', true)->sum('tour_cost');
    }

    /**
     * Returns an array with an overview of all orders in the system
     * @param bool $historic Should the overview contain orders considered historic (default: false)
     * @return array
     */
    public static function getOrdersOverview(bool $historic = false): array
    {
        $orders = Order::with(
            'leadBooker',
            'orderCustomers',
            'orderCustomers.customer',
            'orderCustomers.orderAccommodation',
            'orderCustomers.groups',
            'orderCustomers.orderActivities',
            'orderCustomers.orderFlights',
            'orderCustomers.orderTransports',
            'orderCustomers.orderMerchandise',
        )->whereHas('tour', function ($query) use ($historic) {
            if (!$historic && setting('system.historic', 6) >= 0) {
                return $query->whereDate('date_to', '>', now()->subMonths(setting('system.historic', 6)));
            }
            return $query;
        })->get();
        $data = [];
        foreach ($orders as $order) {
            $data[] = $order->repository->getOverview();
        }
        return $data;
    }

    /**
     * Get an array containing an overview of the order
     * @return array
     */
    public function getOverview(): array
    {
        $travellers = "";
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $travellers .= "{$orderCustomer->customer_name}, ";
        }
        return [
            'ordered' => [
                'unix' => $this->order->ordered_on->unix(),
                'format' => f_datetime($this->order->ordered_on),
            ],
            'lead' => $this->order->lead_booker_name,
            'reference' => $this->order->booking_reference,
            'tour' => $this->order->tour->name,
            'passengers' => $this->order->orderCustomers()->count(),
            'travellers' => $travellers,
            'status' => $this->order->status->getStatusArray(),
            'view' => route('orders.view', ['order' => $this->order,]),
        ];
    }

    /**
     * @param Tour $tour Which tour should the order be created for
     * @param array $data Additional data needed for Order Creation
     * @param ConvertedCustomer $lead Details for the Lead Traveller
     * @param ConvertedCustomer[] $customers Additional Travellers to be added to the order (default: [])
     * @param bool $shouldInvoice Should an invoice be generated for the order (default: true)
     * @return Order
     */
    public static function create(Tour $tour, array $data, ConvertedCustomer $lead, array $customers = [], bool $shouldInvoice = true): Order
    {
        $order = Order::make(['consultant_id' => get_current_admin()?->id, 'tax_bracket_id' => $tour->tax_bracket_id, ...$data]);
        $order->commission = $data['commission'] ?? $lead->customer->organization?->commission;
        $tour->orders()->saveQuietly($order);
        $leadBooker = $order->repository->addCustomer($lead, false, false, true);
        $order->updateQuietly(['lead_booker_id' => $leadBooker->id,]);
        $order->saveQuietly();
        $order->updateQuietly(['booking_reference' => Order::generateBookingReference($order)]); // To Future Me: Must be done separately because Lead Booker ID is *required*
        $order->saveQuietly();
        $included = $tour->repository->getComponentSetForSaving();
        if ($lead->travelling) {
            $leadBooker->repository->bulkSaveStandard($included->clone());
        }
        $order->repository->resetInstallments();
        foreach ($customers as $customer) {
            $orderCustomer = $order->repository->addCustomer($customer, false, false, true);
            if ($customer->travelling) {
                $orderCustomer->repository->bulkSaveStandard($included->clone());
            }
        }
        OrderAccommodation::withoutEvents(static function () use ($order)  {
            RoomingRepository::assignDefaultSharedRooms($order);
        });
        event(new OrderCreatedEvent($order, $shouldInvoice));
        $order->repository->refresh();
        return $order;
    }

    /**
     * Add a new traveller to an order
     * @param ConvertedCustomer $customer The customer to be added to the Order
     * @param bool $refresh Should the cache be refreshed and invoice generated (default: true)
     * @param bool $components Should the components be added (default: true)
     * @param bool $silent
     * @return OrderCustomer
     */
    public function addCustomer(ConvertedCustomer $customer, bool $refresh = true, bool $components = true, bool $silent = false): OrderCustomer
    {
        $orderCustomer = OrderCustomer::make([
            'is_travelling' => $customer->travelling,
            'is_charged' => $customer->paying,
            'customer_id' => $customer->customer->id,
            'tour_cost' => $this->order->tour->base_price_per_person,
            'single_occupancy_surcharge' => $this->order->tour->single_occupancy_surcharge,
            ...$customer->data,
        ]);
        if ($silent) {
            $this->order->orderCustomers()->saveQuietly($orderCustomer);
        } else {
            $this->order->orderCustomers()->save($orderCustomer);
        }
        if ($components) {
            $orderCustomer->repository->addAllIncluded();
            RoomingRepository::assignDefaultRooming($orderCustomer);
        }
        $refresh && $this->refresh();
        !$silent && event(new OrderCustomerCreatedEvent($orderCustomer, $refresh));
        return $orderCustomer;
    }

    /**
     * @param string $reference
     * @return Order|null
     */
    public static function getFromBookingReference(string $reference): ?Order
    {
        return Order::whereBookingReference($reference)->first();
    }

    /**
     * Return the installments with calculated remaining field
     * @return Collection|OrderInstallment[]
     */
    public function getInstallments(bool $final = false): Collection|array
    {
        $customers = $this->order->paying_customers;
        $paid = $this->order->paid - (($this->order->deposit ?? 0.0) * $customers) - ($this->order->booking_fee ?? 0.0);
        DB::statement("SET @total:={$paid};");
        $installments = OrderInstallment::where('order_id', '=', $this->order->id)
            ->orderBy('due_on')
            // max((max(total, 0) - (amount * customers)) * -1), 0) is remaining
            //
            ->selectRaw("*, GREATEST((GREATEST(@total,0)-(amount*{$customers}))*-1,0) as remaining, (@total := @total - (amount*{$customers})) AS running_total");
        $collection = $installments->get();
        if ($final) {
            $collection->add($this->generateRemainingOrderInstallment());
        }
        return $collection;
    }

    public function generateRemainingOrderInstallment(): ?OrderInstallment
    {
        if ($this->order->paying_customers < 1) { return null; }
        return new OrderInstallment([
            'id' => 0,
            'order_id' => $this->order->id,
            'amount' => $this->order->remaining_installment / $this->order->paying_customers,
            'remaining' => min($this->order->remaining, $this->order->remaining_installment),
            'due_on' => $this->order->tour?->final_payment,
        ]);
    }

    /**
     * Returns the invoice repository for a specific invoice, or the most recent one if the requested does not exist
     * @param int $number The invoice number to fetch
     * @return InvoiceRepository The instance of InvoiceRepository related to the invoice
     */
    public function getInvoiceRepository(int $number = 0): InvoiceRepository
    {
        if ($number > 0) {
            $invoice = $this->order->invoices()->where('invoice_number', '=', $number)->first();
            if ($invoice !== null) {
                return $invoice->repository;
            }
        }
        return (new InvoiceGenerator($this->order))->generate()->repository;
    }

    public function generateInvoice(): InvoiceRepository|null
    {
        return (new InvoiceGenerator($this->order))->generate(true)?->repository;
    }

    /**
     * @return AtolRepository The instance of AtolRepository related to this order
     */
    public function getAtolRepository(): AtolRepository
    {
        return $this->atolRepository;
    }

    public function addAdjustment(float $amount, string $reason, Carbon|null $when = null): Model|bool
    {
        if ($when === null) {
            $when = now();
        }
        $adjustment = $this->order->adjustments()->save(ManualAdjustment::make(['amount' => sigfig($amount), 'reason' => $reason, 'date' => $when,]));
        $this->refresh();
        return $adjustment;
    }

    /**
     * Get total cost amount for an order
     * @return float The total cost of the order
     */
    public function getCost(bool $recache = false): float
    {
        if (isset($this->cost) && !$recache) {
            return $this->cost;
        }
        $total = $this->order->booking_fee ?? 0.0;
        foreach ($this->order->orderCustomers()->where('is_charged', '=', 1)->get() as $orderCustomer) {
            $total += $orderCustomer->tour_cost;
            if ($orderCustomer->has_surcharge) { $total += $orderCustomer->single_occupancy_surcharge; }
        }
        $total += $this->getAdditionalComponentTotal();
        $this->cost = $total;
        return $total;
    }

    public function getDepositPayment(): Payment|null
    {
        return $this->getPaymentCoveringAmount($this->order->calculated_deposit + ($this->order->booking_fee ?? 0.0));
    }

    public function getBookingFeePayment(): Payment|null
    {
        return $this->getPaymentCoveringAmount($this->order->booking_fee ?? 0.0);
    }

    public function getRemainingPayment(): Payment|null
    {
        return $this->getPaymentCoveringAmount($this->order->total);
    }

    private function getPaymentCoveringAmount(int|float $amount): Payment|null
    {
        $paidTotal = $this->order->payments()->where('amount', '<', 0)->sum('amount');
        foreach ($this->order->payments as $payment) {
            if ($payment->amount < 0) { continue; }
            $paidTotal += $payment->amount;
            if ($amount <= $paidTotal) { return $payment; }
        }
        return null;
    }

    /**
     * Get all addons and upgrades for an order
     * @return array{addons:array, upgrades:array, additionalValue:float} List of all addons, upgrades, and how much they come to total
     */
    public function getAdditionalCosts(): array
    {
        $addons = [];
        $upgrades = [];
        $additionalValue = 0;
        foreach ($this->order->orderCustomers()->where('is_charged', '=', 1)->get() as $orderCustomer) {
            $data = $orderCustomer->getAdditionalCosts();
            $addons = [...$addons, ...$data['addons']];
            $upgrades = [...$upgrades, ...$data['upgrades']];
            $additionalValue += $data['additionalValue'];
        }
        foreach ($this->order->groups as $group) {
            $data = $group->repository->getAdditionalCosts();
            $addons = [...$addons, ...$data['addons']];
            $upgrades = [...$upgrades, ...$data['upgrades']];
            $additionalValue += $data['additionalValue'];
        }
        return ['upgrades' => $upgrades, 'addons' => $addons, 'additionalValue' => $additionalValue,];
    }

    /**
     * Get the amount the order has left to pay
     * @return float The remaining amount required on the order
     */
    public function getRemaining(): float
    {
        return sigfig(($this->order->cost + $this->order->total_adjustments - $this->order->commission_amount) - $this->order->paid);
    }

    /**
     * @return boolean Whether any customer has a flight
     */
    public function hasFlight(): bool
    {
        $query = DB::table('order_flights')
                    ->join('order_customers', 'order_flights.order_customer_id', '=', 'order_customers.id')
                    ->join('orders', 'order_customers.order_id', '=', 'orders.id')
                    ->where('orders.id', '=', $this->order->id)
                    ->whereNull('order_flights.deleted_at')
                    ->select('order_flights.id')
                    ->get();
        return $query->count() > 0;
    }

    /**
     * @param Customer $customer The customer to check
     * @return bool Whether the specific customer is the lead booker
     */
    public function isLeadBooker(Customer $customer): bool
    {
        return $this->order->leadBooker->customer_id === $customer->id;
    }

    /**
     * @param Customer $customer The customer to find
     * @return OrderCustomer|null The OrderCustomer or null if not found
     */
    public function getOrderCustomer(Customer $customer): ?OrderCustomer
    {
        return $this->order->orderCustomers()->where('customer_id', '=', $customer->id)->first();
    }

    /**
     * Get the current status of the order
     * @return OrderStatus Status code for order
     */
    public function getOrderStatus(bool $forceCache = false): OrderStatus
    {
        if ($this->order->status_override !== null) { $status = $this->order->status_override; }

        /** @var OrderStatus $status */
        if (!isset($status) && !$forceCache) { $status = $this->order->cache?->status; }

        if (!isset($status)) {
            $paidAmount = $this->order->paid;
            $total = sigfig((($this->cost ?? $this->order->cost) + $this->order->total_adjustments) - ($this->order->commission_amount ?? 0.0));
            if ($this->order->cancelled || $this->order->trashed()) {
                if ($paidAmount <= sigfig($this->order->booking_fee ?? 0.0)) {
                    $status = $paidAmount < 0 ? OrderStatus::CANCELLED_OVER_REFUNDED : OrderStatus::CANCELLED_FULL_REFUND;
                }  else if ($paidAmount <= $this->order->calculated_deposit) {
                    $status = OrderStatus::CANCELLED_DEPOSIT_HELD;
                } else {
                    $status = OrderStatus::CANCELLED_REFUND_REQUIRED;
                }
            } else if ($total > $paidAmount) {
                $next = $this->order->next_installment;
                if (isset($next) && Carbon::now()->isAfter($next->due_on)) {
                    $status = OrderStatus::PAYMENT_OVERDUE;
                } else {
                    $status = OrderStatus::BALANCE_OUTSTANDING;
                }
            } else if ($total < $paidAmount) {
                $status = OrderStatus::OVERPAID;
            } else {
                $status = OrderStatus::PAID_IN_FULL;
            }
        }
        return $status;
    }

    /**
     * Get details about the next payment
     * @return OrderInstallment|null Details about the next installment. If installment is null, then no more installments are required
     */
    public function getNextPaymentDetails(bool $includeFinal = true): ?OrderInstallment
    {
        $installment = $this->getInstallments()->firstWhere('remaining', '>', 0);
        if ($includeFinal && $installment === null) {
            $installment = $this->generateRemainingOrderInstallment();
        }
        if ($installment === null) { return null; }
        return $installment->remaining > 0 ? $installment : null;
    }

    public function resetInstallments(): void
    {
        $this->order->installments()->delete();
        foreach ($this->order->tour->paymentInstallments as $installment) {
            $oInstallment = OrderInstallment::make([
                'amount' => $installment->cost,
                'due_on' => $installment->due_on,
            ]);
            $this->order->installments()->save($oInstallment);
        }
        $this->refresh();
    }

    public function hasBeenReminded(OrderInstallment $installment, int $period): bool
    {
        $reminder = PaymentReminder::where('order_id', $this->order->id)->where('order_installment_id', $installment->id)->where('period', $period)->first();
        return isset($reminder);
    }

    public function sendReminderEmails(int $days, int $minDays = -1000): void
    {
        if (!$this->shouldRemind($days, $minDays)) { return; }
        $installment = $this->order->next_installment;
        if ($installment->id > 0) {
            $this->processInstallmentForReminder($this->order->next_installment, $days, $minDays);
        }
    }

    public function sendFinalPaymentEmails(int $days, int $minDays = -1000): void
    {
        $installment = $this->generateRemainingOrderInstallment();
        if ($installment === null) { return; }
        if ($this->shouldRemindForFinal($days, $minDays, $installment)) {
            $this->processInstallmentForReminder($installment, $days, $minDays);
        }
    }

    public function processInstallmentForReminder(OrderInstallment $installment, int $days, int $minDays = -1000): void
    {
        if ($this->hasBeenReminded($installment, $days)) { return; }
        PaymentReminder::create([
            'order_id' => $this->order->id,
            'order_installment_id' => $installment->id,
            'period' => $days
        ]);
        try {
            $final = $installment->id === 0 ? 'final-' : '';
            if ($days < 0) {
                if ($final) {
                    $this->mailer()->sendFinalPaymentDue();
                } else {
                    $this->mailer()->sendPaymentDue();
                }
            } else {
                if ($final) {
                    $this->mailer()->sendFinalPaymentOverdue();
                } else {
                    $this->mailer()->sendPaymentOverdue();
                }
            }
        } catch (MailDisabledException) {}
    }

    public function shouldRemind(int $days, int $minDays = -1000): bool
    {
        $next = $this->getNextPaymentDetails(false);
        if ($next === null) { return false; }
        $daysUntil = days_until($next->due_on);
        return isset($daysUntil) && ($daysUntil <= $days && $daysUntil >= $minDays) && $next->amount >= setting('order.reminders.minimum');
    }

    public function shouldRemindForFinal(int $days, int $minDays = -1000, OrderInstallment $installment = null): bool
    {
        $installment = $installment ?? $this->generateRemainingOrderInstallment();
        if ($installment === null) { return false; }
        $daysUntil = days_until($installment->due_on);
        return $installment->remaining > 0
            && (isset($daysUntil) && ($daysUntil <= $days && $daysUntil >= $minDays))
            && $installment->amount >= setting('order.reminders.minimum');
    }

    public function refresh(): void
    {
        $cache = $this->order->cache ?? new OrderCache(['order_id' => $this->order->id,]);
        $cache->save(); // If the cache isn't saved in the database, then it doesn't write properly for some reason
        $nextPayment = $this->getNextPaymentDetails();
        $cost_to_company = $this->getCostToCompany(true);
        $cache->update([
            'cost' => $this->getCost(true),
            'status' => $this->getOrderStatus(true),
            'total_owed' => $this->order->total,
            'next_payment_date' => $nextPayment?->due_on,
            'next_payment_amount' => $nextPayment?->amount,
            'next_payment_remaining' => $nextPayment?->remaining,
            'commission_amount' => $this->order->commission_amount,
            'cost_to_company' => $cost_to_company,
            'profit' => $this->getCurrentProfit(true, $cost_to_company),
            'cached' => now(),
        ]);
        $cache->save();
    }

    public function getAdditionalComponentTotal():float
    {
        $query = DB::query();
        $query->from(function ($query) {
            $query->from($this->getAccommodationQuery())
                ->union($this->getSingleOwnedTableQuery('activity', 'activities'))
                ->union($this->getSingleOwnedTableQuery('flight', 'flights'))
                ->union($this->getSingleOwnedTableQuery('transport', 'transports'))
                ->union($this->getSingleOwnedTableQuery('merchandise', 'merchandises'))
                ->select('table', 'id', 'cost', 'type');
        });
        $query->select(DB::raw('SUM(`cost`) as total'));
        return $query->first()?->total ?? 0;
    }

    private function getAccommodationQuery(): Builder
    {
        $query = DB::table('order_accommodations');
        $query->join('accommodation_inventory_tours', 'order_accommodations.accommodation_inventory_tour_id', '=', 'accommodation_inventory_tours.id');
        $query->join('groups', 'order_accommodations.group_id', '=', 'groups.id');
        $query->join('order_customer_group', 'order_customer_group.group_id', '=', 'groups.id');
        $query->join('order_customers', 'order_customers.id', '=', 'order_customer_group.order_customer_id');
        $query->where('order_customers.order_id', '=', $this->order->id);
        $query->whereNull('groups.deleted_at');
        $query->whereNull('order_customer_group.deleted_at');
        $query->whereNull('order_customers.deleted_at');
        $query->whereNull('order_accommodations.deleted_at');
        $query->where('accommodation_inventory_tours.tour_component_type', '!=', 'Included');
        $query->groupBy('order_accommodations.id');
        $query->select(DB::raw("'accommodation' as 'table'"), 'order_accommodations.id as id', 'order_accommodations.cost as cost', 'accommodation_inventory_tours.tour_component_type as type');
        return $query;
    }

    public function getSingleOwnedTableQuery(string $single, string $plural): Builder
    {
        $query = DB::table("order_{$plural}");
        $query->join("{$single}_inventory_tours", "order_{$plural}.{$single}_inventory_tour_id", '=', "{$single}_inventory_tours.id");
        $query->join('order_customers', 'order_customers.id', '=', "order_{$plural}.order_customer_id");
        $query->where('order_customers.order_id', '=', $this->order->id);
        $query->whereNull('order_customers.deleted_at');
        $query->whereNull("order_{$plural}.deleted_at");
        $query->where('order_customers.is_charged', '=', 1);
        $query->where("{$single}_inventory_tours.tour_component_type", '!=', 'Included');
        $query->select(DB::raw("'{$single}' as 'table'"), "order_{$plural}.id as id", "order_{$plural}.cost as cost", "{$single}_inventory_tours.tour_component_type as type");
        return $query;
    }

    public function migrate(Tour $tour, bool $resetPrice = true, bool $resetAdjustments = false): void
    {
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $orderCustomer->repository->removeAllComponents(true);
            if ($resetAdjustments) {
                $orderCustomer->adjustments()->delete();
            }
        }
        $this->order->tour_id = $tour->id;
        if ($resetPrice) {
            $this->order->deposit = $tour->deposit_amount;
        }
        $this->order->save();
        $this->order->groups()->delete();
        foreach ($this->order->orderCustomers as $orderCustomer) {
            if ($resetPrice) {
                $orderCustomer->tour_cost = $tour->base_price_per_person;
                $orderCustomer->single_occupancy_surcharge = $tour->single_occupancy_surcharge;
                $orderCustomer->save();
            }
            $orderCustomer->repository->addAllIncluded();
        }
        $this->resetInstallments();
        $order = $this->order;
        OrderAccommodation::withoutEvents(static function () use ($order)  {
            RoomingRepository::assignDefaultSharedRooms($order);
        });
        if ($resetAdjustments) {
            $this->order->adjustments()->delete();
        }
    }

    public function getCostToCompany(bool $recache = false): float
    {
        if (!$recache && $this->order->cache->cost_to_company !== null) {
            return $this->order->cache->cost_to_company;
        }
        $cost = 0;
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $cost += $orderCustomer->repository->getCostToCompany(true);
        }
        $seen = [];
        foreach ($this->order->orderCustomers as $orderCustomer) {
            foreach ($orderCustomer->orderAccommodation as $component) {
                if (in_array($component->id, $seen)) { continue; }
                $cost += $component->repository->getCostToCompany();
                $seen[] = $component->id;
            }
        }
        foreach ($this->order->tour->costs()->where('per_customer', '=', false)->get() as $item) {
            $cost += $item->amount;
        }
        return sigfig($cost);
    }

    public function getCurrentProfit(bool $recache = false, float $cost_to_company): float
    {
        if (!$recache && $this->order->cache->profit !== null) {
            return $this->order->cache->profit;
        }
        $profit = 0;
        $profit = $this->order->total - $cost_to_company;
        return $profit;
    }

    public function getBeforeString(): string|null
    {
        if ($this->order->total_adjustments > 0 || $this->order->commission_amount > 0) {
            $string = fr_currency($this->order->cost, $this->order->currency) . " before ";
            if ($this->order->total_adjustments > 0 && $this->order->commission_amount > 0) {
                $string .= "adjustments and commission";
            } elseif ($this->order->total_adjustments > 0) {
                $string .= "adjustments";
            } elseif ($this->order->commission_amount > 0) {
                $string .= "commission";
            }
            return $string;
        }
        return null;
    }

    public function forceDelete(): void
    {
        $this->order->booking?->repository->forceDelete();
        foreach ($this->order->groups as $group) {
            $group->repository->forceDelete();
        }
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $orderCustomer->repository->forceDelete();
        }
        $this->order->adjustments()->forceDelete();
        foreach ($this->order->invoices as $invoice) {
            $invoice->repository->forceDelete();
        }
        $this->order->payments()->forceDelete();
        $this->order->installments()->forceDelete();
        $this->order->reminders()->forceDelete();
        $this->order->forceDelete();
    }

    public static function generateGenericCustomer(string $first, string $last): Customer
    {
        $homeAddress = Address::create([
            'name' => 'Generic Customer Address',
            'parent' => AddressParent::CUSTOMER,
        ]);
        $billingAddress = $homeAddress->repository->cloneToNew(AddressParent::CUSTOMER);
        return Customer::create([
            'first_name' => $first,
            'last_name' => $last,
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            'date_of_birth' => now(),
        ]);
    }

    public function getFellohData(): array
    {
        return [
            'customer_name' => $this->order->leadBooker->customer_name,
            'email' => $this->order->leadBooker->customer->email_address,
            'booking_reference' => $this->getReference(),
            'departure_date' => $this->order->tour->date_from->format('Y-m-d'),
            'return_date' => $this->order->tour->date_to->format('Y-m-d'),
            'gross_amount' => (int)($this->order->total*100),
        ];
    }

    public function getReference(): string
    {
        return $this->order->booking_reference;
    }

    public function getFellohId(): string|null
    {
        return $this->order->felloh?->felloh_id;
    }

    public function setFellohId(string $id): void
    {
        $current = $this->getFellohId();
        if ($current === $id) { return; }
        if ($current !== null) {
            $this->order->felloh->felloh_id = $id;
            $this->order->felloh->save();
        } else {
            $this->order->felloh()->save(new FellohLink(['felloh_id' => $id]));
        }
    }

    /**
     * @return array<int, ItineraryItem[]> Array of itinerary items, with the int representing the unix timestamp
     */
    public function getItineraryItems(): array
    {
        $items = [];
        $seen = [];

        foreach ($this->getAccommodationForItinerary() as $item) {
            $heading = "Accommodation";
            if (!array_key_exists($heading, $items)) { $items[$heading] = []; }
            $items[$heading][] = $item;
        }

        foreach ($this->order->orderActivities()->groupBy('activity_inventory_tour_id')->get() as  $component) {
            $key = "activity-{$component->activity_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            $start = $component->tourComponent->inventory->starts_at->clone()->setTime(0,0)->unix();
            if (!array_key_exists($start, $items)) { $items[$start] = []; }
            $items[$start][] = $item;
        }

        foreach ($this->order->orderFlights()->groupBy('flight_inventory_tour_id')->get() as  $component) {
            $key = "flight-{$component->flight_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            $start = $component->tourComponent->inventory->departs_at->clone()->setTime(0,0)->unix();
            if (!array_key_exists($start, $items)) { $items[$start] = []; }
            if ($component->tourComponent->inventory->component->activity_category === ActivityCategory::MAIN) {
                $item->name = $this->order->tour?->event?->name ?? $item->name;
            }
            $items[$start][] = $item;
        }

        foreach ($this->order->orderTransport()->groupBy('transport_inventory_tour_id')->get() as  $component) {
            $key = "transport-{$component->transport_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            $start = $component->repository->getStartTime()->clone()->setTime(0,0)->unix();
            if (!array_key_exists($start, $items)) { $items[$start] = []; }
            $items[$start][] = $item;
        }
        ksort($items);
        return $items;
    }

    private function getTravellerItineraryArray(): array
    {
        $travellers = [];
        foreach ($this->order->orderCustomers as $traveller) {
            if ($traveller->id === $this->order->lead_booker_id) { continue; }
            $travellers[] = new ItineraryTraveller(
                $traveller->customer,
                $traveller->is_charged,
                $traveller->is_travelling,
            );
        }
        return $travellers;
    }

    public function getPaymentItineraryArray(): array
    {
        $payments = [];
        foreach ($this->order->payments as $payment) {
            $payments[] = new ItineraryPayment($payment->paid_on, $payment->amount, $payment->payment_type, $payment->payer_name);
        }
        return $payments;
    }

    public function getScheduleItineraryArray(): array
    {
        $schedule = [];
        if ($this->order->booking_fee > 0) {
            $covering = $this->order->repository->getBookingFeePayment();
            $paid_on = ($covering !== null) ? $covering->paid_on : null;
            $schedule[] = new ItinerarySchedule(ItineraryScheduleType::BOOKING_FEE, null, $this->order->booking_fee, null, $this->order->booking_fee <= $this->order->paid, $paid_on,  $this->order->paid);
        }
        if ($this->order->calculated_deposit > 0) {
            $covering = $this->order->repository->getDepositPayment();
            $paid_on = ($covering !== null) ? $covering->paid_on : null;
            $schedule[] = new ItinerarySchedule(ItineraryScheduleType::DEPOSIT, null, $this->order->calculated_deposit, $this->order->deposit_percentage, $this->order->deposit_paid, $paid_on, $this->order->paid, $this->order->booking_fee);
        }
        foreach ($this->getInstallments() as $installment) {
            $received = $installment->repository->getAmountPaid();
            $schedule[] = new ItinerarySchedule(ItineraryScheduleType::INSTALLMENT, $installment->due_on, $installment->calculated_amount, $installment->percentage, $installment->paid, $installment->paid_on, $received);
        }
        if ($this->order->remaining_installment > 0) {
            $covering = $this->order->repository->getRemainingPayment();
            $paid_on = ($covering !== null) ? $covering->paid_on : null;
            $schedule[] = new ItinerarySchedule(ItineraryScheduleType::REMAINING, $this->order->tour->final_payment, $this->order->remaining_installment, $this->order->remaining_percentage, $this->order->remaining <= 0, $paid_on, $this->order->paid, $this->order->remaining);
        }
        $schedule[] = new ItinerarySchedule(ItineraryScheduleType::TOTAL, null, $this->order->paid, $this->order->remaining);
        return $schedule;
    }

    private function getItineraryFinances(): ItineraryPaymentDetails
    {
        return new ItineraryPaymentDetails(
            $this->order->total,
            $this->order->getTaxes(),
            $this->order->commission_amount,
            $this->order->commission,
            $this->order->cost,
            $this->getScheduleItineraryArray(),
            $this->getPaymentItineraryArray(),
        );
    }

    private function getGenericItinerary(): Itinerary
    {
        return new Itinerary(
            $this->order->tour->name,
            $this->order->tour->event,
            $this->order->tour->event?->description ?? $this->order->tour->description,
            $this->order->tour->event?->image_url,
            $this->order->tour->event?->banner_url,
            $this->order->booking_reference,
            $this->order->organization,
            $this->order->agent,
            $this->order->consultant ?? Auth::user(),
            $this->order->tour->date_from,
            $this->order->tour->date_to,
            $this->order->ordered_on,
            new ItineraryTraveller($this->order->leadBooker->customer, $this->order->leadBooker->is_charged, $this->order->leadBooker->is_travelling),
            $this->order->tour->brand,
            $this->getTravellerItineraryArray(),
            $this->getReservationComponents(),
            $this->getItineraryFinances(),
            $this->order->tour->terms,
            $this->order->invoice_footer,
            $this->order->external_notes,
        );
    }

    public function getItinerary(): Itinerary
    {
        $itinerary = $this->getGenericItinerary();
        $itinerary->finances = null;

        return $itinerary;
    }

    public function getReservationComponents(): array
    {
        $items = [];
        $seen = [];

        foreach ($this->getAccommodationForItinerary() as $item) {
            $heading = "Accommodation";
            if (!array_key_exists($heading, $items)) { $items[$heading] = []; }
            $items[$heading][] = $item;
        }

        foreach ($this->order->orderActivities()->groupBy('activity_inventory_tour_id')->get() as  $component) {
            $key = "activity-{$component->activity_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            $isMain = $component->tourComponent->inventory->component->activity_category === ActivityCategory::MAIN;
            $header = $isMain ? 'Event' : 'Inclusion';
            if (!array_key_exists($header, $items)) { $items[$header] = []; }
            if ($isMain) {
                $item->name = $component->orderCustomer->order?->tour?->event->name ?? $item->name;
            }
            $items[$header][] = $item;
        }

        foreach ($this->order->orderFlights()->groupBy('flight_inventory_tour_id')->get() as  $component) {
            $key = "flight-{$component->flight_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            if (!empty($component->flight_number_override)){
                $item->details['Booking Reference'] = $component->flight_number_override;
            }
            $header = "Flights";
            if (!array_key_exists($header, $items)) { $items[$header] = []; }
            $items[$header][] = $item;
        }

        foreach ($this->order->orderTransport()->groupBy('transport_inventory_tour_id')->get() as  $component) {
            $key = "transport-{$component->transport_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            if (!empty($component->departs_at_time_override)){
                $item->details['Time'] = $component->departs_at_time_override->format('H:i');
            }
            $header = "Transfers";
            if (!array_key_exists($header, $items)) { $items[$header] = []; }
            $items[$header][] = $item;
        }

        foreach ($this->order->orderMerchandise()->groupBy('merchandise_inventory_tour_id')->get() as  $component) {
            $key = "merchandise-{$component->merchandise_inventory_tour_id}";
            if (in_array($key, $seen, true)) { continue; }
            $seen[] = $key;
            $item = $component->repository->getItineraryItem($this->order);
            $header = "Inclusion";
            if (!array_key_exists($header, $items)) { $items[$header] = []; }
            $items[$header][] = $item;
        }
        foreach ($items as $header => $data) {
            usort($data, static function (ItineraryItem $a, ItineraryItem $b) { return ($a->sortKey <=> $b->sortKey); });
            $items[$header] = $data;
        }
        return $items;
    }

    public function getReservationDocument(): Itinerary
    {
        $itinerary = $this->getGenericItinerary();
        $itinerary->package = null;
        $itinerary->items = $this->getReservationComponents();
        return $itinerary;
    }

    /**
     * @return ItineraryItem[]
     */
    public function getAccommodationForItinerary(): array
    {
        $data = [];
        /** @var AccommodationByDateStorage[] $byDate */
        $byDate = [];
        foreach ($this->order->groups as $group) {
            foreach ($group->rooms as $component) {
                $found = false;
                foreach ($byDate as $storage) {
                    if ($storage->matches($component->tourComponent->inventory)) {
                        $storage->addRoom($component->tourComponent->inventory);
                        $found = true;
                    }
                }
                if (!$found) {
                    $byDate[] = AccommodationByDateStorage::createFromInventory($component->tourComponent->inventory);
                }
            }
        }

        foreach ($byDate as $item) {
            $data = [
                ...$data,
                ...$item->getItineraryLines($this->order),
            ];
        }
        return $data;
    }

    public function getQuantity(OrderAccommodation|OrderActivity|OrderFlight|OrderTransport|OrderMerchandise $component): int
    {
        return match (true) {
            $component instanceof OrderAccommodation => $this->getAccommodationQuantity($component),
            $component instanceof OrderActivity => $this->order->orderActivities()->where('activity_inventory_tour_id', '=', $component->activity_inventory_tour_id)->count(),
            $component instanceof OrderFlight => $this->order->orderFlights()->where('flight_inventory_tour_id', '=', $component->flight_inventory_tour_id)->count(),
            $component instanceof OrderTransport => $this->order->orderTransport()->where('transport_inventory_tour_id', '=', $component->transport_inventory_tour_id)->count(),
            $component instanceof OrderMerchandise => $this->order->orderMerchandise()->where('merchandise_inventory_tour_id', '=', $component->merchandise_inventory_tour_id)->count(),
        };
    }

    public function getAccommodationQuantity(OrderAccommodation $component): int
    {
        $count = 0;
        foreach ($this->order->groups as $group) {
            foreach ($group->rooms as $room) {
                if ($room->accommodation_inventory_tour_id === $component->accommodation_inventory_tour_id) {
                    ++$count;
                }
            }
        }
        return $count;
    }

    /**
     * Returns all OrderAccommodation on the order, sorted by check_in
     *
     * @return OrderAccommodation[]
     */
    public function getOrderAccommodationByStart(): array
    {
        $rooms = [];
        foreach ($this->order->groups as $group) {
            foreach ($group->rooms as $room) {
                $rooms[] = $room;
            }
        }
        usort($rooms, static function (OrderAccommodation $a, OrderAccommodation $b) {
            if ($a->tourComponent?->inventory === null) return -1;
            if ($b->tourComponent?->inventory === null) return 1;
            return AccommodationInventoryRepository::compareTwo($a->tourComponent->inventory, $b->tourComponent->inventory);
        });
        return $rooms;
    }

    /**
     * Return a list of merged accommodation
     *
     * @return MergedAccommodation[]
     */
    public function getMergedAccommodation(): array
    {
        /** @var MergedAccommodation[] $merged */
        $merged = [];
        foreach ($this->getOrderAccommodationByStart() as $room) {
            if ($room->tourComponent?->inventory === null) { continue; }
            $found = false;
            foreach ($merged as $key => $merge) {
                if ($merge->addToMerge($room)) {
                    $found = true;
                    $merged[$key] = $merge;
                    break;
                }
            }
            if (!$found) {
                $merged[] = MergedAccommodation::make($room);
            }
        }
        return $merged;
    }
}
