<?php

namespace App\Repository\Model\Order;

use App\Events\Order\OrderCreatedEvent;
use App\Models\Customer\Customer;
use App\Models\Helper\OrderStatus;
use App\Models\Location\Address;
use App\Models\Location\AddressParent;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use App\Models\Order\Payment\PaymentReminder;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Mailing\MailRepository;
use App\Repository\RoomingRepository;
use App\Repository\Storage\ConvertedCustomer;
use App\Repository\Storage\RemoteGroup;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends ModelRepository
{
    private const STATUS_CACHE_TIME = 600;
    private Order $order;
    private AtolRepository $atolRepository;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->atolRepository = new AtolRepository($order);
    }

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
            if (!$historic) {
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
     * @param Tour $tour
     * @param array $data
     * @param ConvertedCustomer $lead
     * @param ConvertedCustomer[] $customers
     * @param bool $shouldInvoice
     * @return Order
     */
    public static function create(Tour $tour, array $data, ConvertedCustomer $lead, array $customers = [], bool $shouldInvoice = true): Order
    {
        $order = Order::make($data);
        $tour->orders()->save($order);
        $leadBooker = $order->repository->addCustomer($lead);
        $order->repository->update(['lead_booker_id' => $leadBooker->id,]);
        $order->repository->update(['booking_reference' => Order::generateBookingReference($order),]); // Merging will lead to lead booker id not being set at generation
        $included = $tour->repository->getComponentSetForSaving();
        $defaultRooms = RoomingRepository::getDefaultRoomList($tour);
        $leadBooker->repository->bulkSaveStandard($included->clone());
        RoomingRepository::createGroupFromRoomList($leadBooker, $defaultRooms);
        $order->repository->resetInstallments();
        foreach ($customers as $customer) {
            $orderCustomer = $order->repository->addCustomer($customer);
            $orderCustomer->repository->bulkSaveStandard($included->clone());
            RoomingRepository::createGroupFromRoomList($orderCustomer, $defaultRooms);
        }
        event(new OrderCreatedEvent($order, $shouldInvoice));
        return $order;
    }

    public function addCustomer(ConvertedCustomer $customer): OrderCustomer
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
    public function getInstallments(): Collection|array
    {
        $customers = $this->order->orderCustomers()->count();
        $paid = $this->order->paid - ($this->order->deposit * $customers);
        DB::statement("SET @total:={$paid};");
        $installments = OrderInstallment::where('order_id', '=', $this->order->id)
            ->orderBy('due_on')
            ->selectRaw("*, GREATEST((GREATEST(@total,0)-(amount*{$customers}))*-1,0) as remaining, (@total := @total - (amount*{$customers})) AS rt");
        return $installments->get();
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

    public function addAdjustment(float $amount, string $reason, Carbon $when)
    {
        
    }

    /**
     * Get total cost amount for an order
     * @return float The total cost of the order
     */
    public function getCost(): float
    {
        $total = 0;
        foreach ($this->order->orderCustomers()->where('is_charged', '=', 1)->get() as $orderCustomer) {
            $total += $orderCustomer->tour_cost;
            if ($orderCustomer->has_surcharge) $total += $orderCustomer->single_occupancy_surcharge;
        }
        $total += $this->getAdditionalComponentTotal();
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
     * Get the sum of the customer and order adjustments
     * @return float Sum of the two adjustment values
     */
    public function getTotalAdjustedValue(): float
    {
        return $this->getCustomerAdjustmentTotal() + $this->order->order_adjustment_total;
    }

    /**
     * Get the sum of the Customer Adjustments
     * @return float The sum of the customer adjustments
     */
    public function getCustomerAdjustmentTotal(): float
    {
        $total = 0;
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $total += $orderCustomer->adjustment_total;
        }
        return $total;
    }

    /**
     * @return boolean Whether any customer has a flight
     */
    public function hasFlight(): bool
    {
        $query = DB::table('order_flights');
        $query->join('order_customers', 'order_flights.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('orders.id', '=', $this->order->id);
        $query->whereNull('order_flights.deleted_at');
        $query->select('order_flights.id');
        $results = $query->get();
        return $results->count() > 0;
    }

    public function get(): Order
    {
        return $this->order;
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
        foreach ($this->order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) return $orderCustomer;
        }
        return null;
    }

    /**
     * Get the current status of the order
     * @return OrderStatus Status code for order
     */
    public function getOrderStatus(bool $forceCache = false): OrderStatus
    {
        /** @var OrderStatus $status */
        if (!$forceCache) {
            $status = Cache::get("orders.{$this->order->id}.status");
        }
        if (!isset($status)) {
            $paidAmount = $this->order->paid;
            $cost = $this->order->cost;
            $adjustments = $this->order->total_adjustments;
            $total = $cost + $adjustments;
            if ($this->order->trashed() || $this->order->cancelled) {
                if ($paidAmount <= 0) {
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
    public function getNextPaymentDetails(): ?OrderInstallment
    {
        return $this->getInstallments()->firstWhere('remaining', '>', 0);
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

    public function delete(): bool
    {
        return $this->order->delete();
    }

    public function save(): bool
    {
        return $this->order->save();
    }

    public function hasBeenReminded(OrderInstallment $installment): bool
    {
        $reminder = PaymentReminder::where('order_id', $this->order->id)->where('order_installment_id', $installment->id)->first();
        return isset($reminder);
    }

    public function sendReminderEmails(int $days, int $minDays = -1000): void
    {
        if (!$this->shouldRemind($days, $minDays)) return;
        $nextInstallment = $this->order->next_installment;
        if ($this->hasBeenReminded($nextInstallment)) return;
        PaymentReminder::create([
            'order_id' => $this->order->id,
            'order_installment_id' => $nextInstallment->id,
            'period' => $days
        ]);
        if ($days < 0) {
            MailRepository::sendMailable('payment-overdue', $this->order->leadBooker->customer->email_address, $this->order);
        } else {
            MailRepository::sendMailable('payment-due', $this->order->leadBooker->customer->email_address, $this->order);
        }
    }

    public function shouldRemind(int $days, int $minDays = -1000): bool
    {
        $daysUntil = $this->order->days_until_next_payment;
        return isset($daysUntil) && ($daysUntil <= $days && $daysUntil >= $minDays);
    }

    public function update(array $data): Order
    {
        $this->order->update($data);
        $this->save();
        return $this->get();
    }

    public function isDeleted(): bool
    {
        return $this->order->trashed();
    }

    public function __toString(): string
    {
        return "Order {$this->order->booking_reference}: {$this->order->tour->name} ({$this->order->lead_booker_name})";
    }

    public function refresh()
    {
        $this->getOrderStatus(true);
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

    public function migrate(Tour $tour, bool $resetPrice = true, bool $resetAdjustments = false)
    {
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $orderCustomer->repository->removeAllComponents();
            if ($resetAdjustments) {
                $orderCustomer->adjustments()->delete();
            }
        }
        $this->order->tour_id = $tour->id;
        if ($resetPrice) {
            $this->order->deposit = $tour->deposit;
        }
        $this->order->save();
        foreach ($this->order->orderCustomers as $orderCustomer) {
            if ($resetPrice) {
                $orderCustomer->tour_cost = $tour->base_price_per_person;
                $orderCustomer->single_occupancy_surcharge = $tour->single_occupancy_surcharge;
                $orderCustomer->save();
            }
            $orderCustomer->repository->addAllIncluded();
        }
        $this->resetInstallments();
        foreach ($this->order->groups as $group) {
            $group->repository->refreshRooming();
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

    public function getRoomingData(): array
    {
        $rooms = [];
        foreach ($this->order->tour->accommodationInventoryTours()->with('inventory', 'inventory.component')->get() as $inventoryTour) {
            $rooms[$inventoryTour->id] = [
                'name' => $inventoryTour->repository->formatAdminOccupancy(),
                'size' => $inventoryTour->inventory->roomType->maximum_occupancy,
                'price' => $inventoryTour->tour_component_type === 'Included' ? 0 : $inventoryTour->tour_sales_price,
                'start' => $inventoryTour->inventory->check_in->unix(),
                'end' => $inventoryTour->inventory->check_out->unix(),
            ];
        }
        $customers = [];
        foreach ($this->order->orderCustomers()->with('customer')->get() as $orderCustomer) {
            $customers[$orderCustomer->id] = ['name' => $orderCustomer->customer_name, 'avatar' => $orderCustomer->customer->avatar_url,];
        }
        $groups = [];
        foreach ($this->order->groups as $group) {
            $groupCustomers = [];
            foreach ($group->orderCustomers as $orderCustomer) {
                $groupCustomers[] = $orderCustomer->id;
            }
            $groupRooms = [];
            foreach ($group->rooms as $room) {
                $groupRooms[] = $room->accommodation_inventory_tour_id;
            }
            $groups[$group->id] = ['rooms' => $groupRooms, 'customers' => $groupCustomers,];
        }
        return ['rooms' => $rooms, 'customers' => $customers, 'groups' => $groups,];
    }

    private function wipeGroups(): void
    {
        foreach ($this->order->groups as $group) {
            $group->rooms()->delete();
            $group->pivot()->delete();
            $group->delete();
        }
    }

    /**
     * @param RemoteGroup[] $remoteGroups
     * @return void
     */
    public function importRoomingData(array $remoteGroups): void
    {
        $this->wipeGroups();
        foreach ($remoteGroups as $remoteGroup) {
            $remoteGroup->convertToGroup();
        }
    }

    public function forceDelete(): void
    {
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
            'address_parent_id' => AddressParent::getParentId('customer'),
        ]);
        $billingAddress = $homeAddress->repository->cloneToNew(AddressParent::getParentId('customer'));
        return Customer::create([
            'first_name' => $first,
            'last_name' => $last,
            'home_address_id' => $homeAddress->id,
            'billing_address_id' => $billingAddress->id,
            'date_of_birth' => now(),
        ]);
    }
}
