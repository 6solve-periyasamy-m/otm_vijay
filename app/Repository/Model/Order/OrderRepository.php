<?php

namespace App\Repository\Model\Order;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Exceptions\MailDisabledException;
use App\Mail\Storage\OrderMail;
use App\Models\Customer\Customer;
use App\Models\Helper\AddressParent;
use App\Models\Helper\OrderStatus;
use App\Models\Location\Address;
use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use App\Models\Order\Payment\PaymentReminder;
use App\Models\System\FellohLink;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Interfaces\GeneratesFellohData;
use App\Repository\Mailing\Mailer\Order\OrderMailer;
use App\Repository\RoomingRepository;
use App\Repository\Storage\ConvertedCustomer;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends ModelRepository implements GeneratesFellohData
{
    private const STATUS_CACHE_TIME = 600;
    private Order $order;
    private AtolRepository $atolRepository;
    private float|null $cost = null;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->atolRepository = new AtolRepository($order);
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
     * @return OrderMailer
     */
    public function mailer(): OrderMailer
    {
        return new OrderMailer($this->order);
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
            } else {
                return $query;
            }
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
        $order = Order::make($data);
        $tour->orders()->save($order);
        $leadBooker = $order->repository->addCustomer($lead, false, false);
        $order->repository->update(['lead_booker_id' => $leadBooker->id,]);
        $order->repository->update(['booking_reference' => Order::generateBookingReference($order),]); // Merging will lead to lead booker id not being set at generation
        $included = $tour->repository->getComponentSetForSaving();
        $defaultRooms = RoomingRepository::getDefaultRoomList($tour);
        if ($lead->travelling) {
            $leadBooker->repository->bulkSaveStandard($included->clone());
            RoomingRepository::createGroupFromRoomList($leadBooker, $defaultRooms);
        }
        $order->repository->resetInstallments();
        foreach ($customers as $customer) {
            $orderCustomer = $order->repository->addCustomer($customer, false, false);
            if ($customer->travelling) {
                $orderCustomer->repository->bulkSaveStandard($included->clone());
                RoomingRepository::createGroupFromRoomList($orderCustomer, $defaultRooms);
            }
        }
        event(new OrderCreatedEvent($order, $shouldInvoice));
        $order->refresh();
        return $order;
    }

    /**
     * Add a new traveller to an order
     * @param ConvertedCustomer $customer The customer to be added to the Order
     * @param bool $refresh Should the cache be refreshed and invoice generated (default: true)
     * @param bool $components Should the components be added (default: true)
     * @return OrderCustomer
     */
    public function addCustomer(ConvertedCustomer $customer, bool $refresh = true, bool $components = true): OrderCustomer
    {
        $orderCustomer = OrderCustomer::make([
            'is_travelling' => $customer->travelling,
            'is_charged' => $customer->paying,
            'customer_id' => $customer->customer->id,
            'tour_cost' => $this->order->tour->base_price_per_person,
            'single_occupancy_surcharge' => $this->order->tour->single_occupancy_surcharge,
            ...$customer->data,
        ]);
        $this->order->orderCustomers()->save($orderCustomer);
        if ($components) {
            $orderCustomer->repository->addAllIncluded();
            RoomingRepository::assignDefaultRooming($orderCustomer);
        }
        $refresh && $this->refresh();
        event(new OrderCustomerCreatedEvent($orderCustomer, $refresh));
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
        $customers = $this->order->orderCustomers()->count();
        $paid = $this->order->paid - (($this->order->deposit ?? 0) * $customers) - ($this->order->booking_fee ?? 0);
        DB::statement("SET @total:={$paid};");
        $installments = OrderInstallment::where('order_id', '=', $this->order->id)
            ->orderBy('due_on')
            ->selectRaw("*, GREATEST((GREATEST(@total,0)-(amount*{$customers}))*-1,0) as remaining, (@total := @total - (amount*{$customers})) AS rt");
        $collection = $installments->get();
        if ($final) {
            $collection->add($this->generateRemainingOrderInstallment());
        }
        return $collection;
    }

    public function generateRemainingOrderInstallment(): OrderInstallment
    {
        return new OrderInstallment([
            'id' => 0,
            'order_id' => $this->order->id,
            'amount' => $this->order->remaining_installment / $this->order->orderCustomers()->count(),
            'remaining' => min($this->order->remaining, $this->order->remaining_installment),
            'due_on' => $this->order->tour->final_payment,
        ]);
    }

    /**
     * Returns the invoice repository for a specific invoice, or the most recent one if the requested does not exist
     * @param int $number The invoice number to fetch
     * @return InvoiceRepository The instance of InvoiceRepository related to the invoice
     */
    public function getInvoiceRepository(int $number = 0): InvoiceRepository
    {
        return InvoiceRepository::getInvoiceById($this->order, $number);
    }

    /**
     * @return AtolRepository The instance of AtolRepository related to this order
     */
    public function getAtolRepository(): AtolRepository
    {
        return $this->atolRepository;
    }
    
    public function savePayment(Payment $payment): Payment
    {
        $this->order->payments()->save($payment);
        $this->refresh();
        return $payment;
    }

    public function addInstallment(Carbon $due, float $amount): OrderInstallment
    {
        $installment = OrderInstallment::create([
            'due_on' => $due,
            'amount' => $amount,
        ]);
        $this->refresh();
        return $installment;
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
        if ($this->cost !== null && !$recache) {
            return $this->cost;
        }
        $total = $this->order->booking_fee ?? 0;
        foreach ($this->order->orderCustomers()->where('is_charged', '=', 1)->get() as $orderCustomer) {
            $total += $orderCustomer->tour_cost;
            if ($orderCustomer->has_surcharge) $total += $orderCustomer->single_occupancy_surcharge;
        }
        $total += $this->getAdditionalComponentTotal();
        $this->cost = $total;
        return $total;
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
            $addons = array_merge($addons, $data['addons']);
            $upgrades = array_merge($upgrades, $data['upgrades']);
            $additionalValue += $data['additionalValue'];
        }
        foreach ($this->order->groups as $group) {
            $data = $group->repository->getAdditionalCosts();
            $addons = array_merge($addons, $data['addons']);
            $upgrades = array_merge($upgrades, $data['upgrades']);
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
        return sigfig(($this->order->cost + $this->order->total_adjustments) - $this->order->paid);
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
        return $this->order->leadBooker->customer_id == $customer->id;
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
        if ($this->order->status_override !== null) {
            $status = $this->order->status_override;
        }
        /** @var OrderStatus $status */
        if (!isset($status) && !$forceCache) {
            $status = Cache::get("orders.{$this->order->id}.status");
        }
        if (!isset($status)) {
            $paidAmount = $this->order->paid;
            $cost = $this->order->cost;
            $adjustments = $this->order->total_adjustments;
            $total = $cost + $adjustments;
            if ($this->order->trashed() || $this->order->cancelled) {
                if ($paidAmount <= ($this->order->booking_fee ?? 0)) {
                    $status = $paidAmount < 0 ? OrderStatus::CANCELLED_OVER_REFUNDED : OrderStatus::CANCELLED_FULL_REFUND;
                }  else if ($paidAmount <= $this->order->calculated_deposit) {
                    $status = OrderStatus::CANCELLED_DEPOSIT_HELD;
                } else {
                    $status = OrderStatus::CANCELLED_REFUND_REQUIRED;
                }
            } else {
                if ($total > $paidAmount) {
                    $next = $this->order->next_installment;
                    if (isset($next) && Carbon::now()->isAfter($next->due_on)) {
                        $status = OrderStatus::PAYMENT_OVERDUE;
                    } else {
                        $status = OrderStatus::BALANCE_OUTSTANDING;
                    }
                } elseif ($total < $paidAmount) {
                    $status = OrderStatus::OVERPAID;
                } else {
                    $status = OrderStatus::PAID_IN_FULL;
                }
            }
            Cache::put("orders.{$this->order->id}.status", $status, self::STATUS_CACHE_TIME);
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
        if ($installment === null) return null;
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
        if (!$this->shouldRemind($days, $minDays)) return;
        $installment = $this->order->next_installment;
        if ($installment->id > 0) {
            $this->processInstallmentForReminder($this->order->next_installment, $days, $minDays);
        }
    }

    public function sendFinalPaymentEmails(int $days, int $minDays = -1000): void
    {
        $installment = $this->generateRemainingOrderInstallment();
        if ($this->shouldRemindForFinal($days, $minDays, $installment)) {
            $this->processInstallmentForReminder($installment, $days, $minDays);
        }
    }

    public function processInstallmentForReminder(OrderInstallment $installment, int $days, int $minDays = -1000): void
    {
        if ($this->hasBeenReminded($installment, $days)) return;
        PaymentReminder::create([
            'order_id' => $this->order->id,
            'order_installment_id' => $installment->id,
            'period' => $days
        ]);
        try {
            $prefix = $installment->id === 0 ? 'final-' : '';
            if ($days < 0) {
                (new OrderMail($prefix . 'payment-overdue'))->send($this->order->leadBooker->customer->email_address, $this->order);
            } else {
                (new OrderMail($prefix . 'payment-due'))->send($this->order->leadBooker->customer->email_address, $this->order);
            }
        } catch (MailDisabledException) {}
    }

    public function shouldRemind(int $days, int $minDays = -1000): bool
    {
        $daysUntil = $this->order->days_until_next_payment;
        return isset($daysUntil) && ($daysUntil <= $days && $daysUntil >= $minDays);
    }

    public function shouldRemindForFinal(int $days, int $minDays = -1000, OrderInstallment $installment = null): bool
    {
        $installment = $installment ?? $this->generateRemainingOrderInstallment();
        $daysUntil = days_until($installment->due_on);
        return $installment->remaining > 0 && (isset($daysUntil) && ($daysUntil <= $days && $daysUntil >= $minDays));
    }

    public function refresh(): void
    {
        $this->getOrderStatus(true);
        $this->getCost(true);
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
            $this->order->deposit = $tour->deposit;
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
        foreach ($this->order->orderCustomers as $orderCustomer) {
            RoomingRepository::assignDefaultRooming($orderCustomer);
        }
        if ($resetAdjustments) {
            $this->order->adjustments()->delete();
        }
    }

    public function getCostToCompany(): float
    {
        $cost = 0;
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $cost += $orderCustomer->repository->getCostToCompany();
        }
        return $cost;
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
        $this->order->invoices()->forceDelete();
        $this->order->payments()->forceDelete();
        $this->order->installments()->forceDelete();
        $this->order->reminders()->forceDelete();
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
}
