<?php

namespace App\Repository\Model\Order;

use App\Models\Customer\Customer;
use App\Models\Helper\OrderStatus;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\PaymentReminder;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\Mailing\MailRepository;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository extends ModelRepository
{
    private Order $order;
    private AtolRepository $atolRepository;
    private const STATUS_CACHE_TIME = 600;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->atolRepository = new AtolRepository($order);
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

    /**
     * Get total cost amount for an order
     * @return float The total cost of the order
     */
    public function getCost(): float
    {
        $total = 0;
        foreach ($this->order->orderCustomers as $orderCustomer) {
            $total += $orderCustomer->tour_cost;
            if ($orderCustomer->has_surcharge) $total += $orderCustomer->single_occupancy_surcharge;
            foreach ($orderCustomer->repository->getComponents(false) as $component) {
                if ($component->getTourComponentType() !== "Included") {
                    $total += $component->getCost();
                }
            }
        }
        foreach ($this->order->groups as $group) {
            foreach ($group->rooms as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
        }
        return $total;
    }

    /**
     * Get the amount the order has left to pay
     * @return float The remaining amount required on the order
     */
    public function getRemaining(): float
    {
        return ($this->order->cost + $this->order->total_adjustments) - $this->order->paid;
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
                if ($paidAmount == 0) {
                    $status = OrderStatus::CANCELLED_FULL_REFUND;
                } else if ($paidAmount <= $this->order->calculated_deposit) {
                    $status = OrderStatus::CANCELLED_DEPOSIT_HELD;
                } else {
                    $status = OrderStatus::CANCELLED_REFUND_REQUIRED;
                }
            } else {
                foreach ($this->order->orderCustomers as $orderCustomer) {
                    if (!$orderCustomer->has_occupancy) {
                        $status = OrderStatus::OCCUPANCY_NOT_SET;
                        Cache::put("orders.{$this->order->id}.status", $status, self::STATUS_CACHE_TIME);
                        return $status;
                    }
                }
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
        $paid = $this->order->paid;
        $paid -= $this->order->total_adjustments; // Negative adjustments add to the total paid, so minus is required
        $paid -= $this->order->calculated_deposit; // Deposit must be removed as it is an installment, but not treated as one (Celeste)
        $paid = sigfig($paid);
        foreach ($this->order->installments as $installment) {
            $paid -= $installment->calculated_amount;
            $paid = sigfig($paid);
            if ($paid < 0) {
                return new OrderInstallment([
                    'id' => $installment->id,
                    'amount' => min($installment->calculated_amount, $paid * -1),
                    'due_on' => $installment->due_on,
                    'order_id' => $this->order->id,
                ]);
            }
        }
        return null;
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
    }

    public function shouldRemind(int $days, int $minDays = -1000): bool
    {
        $daysUntil = $this->order->days_until_next_payment;
        return isset($daysUntil) && ($daysUntil <= $days && $daysUntil >= $minDays);
    }

    public function sendReminderEmails(int $days, int $minDays = -1000): void
    {
        if (!$this->shouldRemind($days, $minDays)) return;
        $nextInstallment = $this->order->next_installment;
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

    public function get(): Order
    {
        return $this->order;
    }

    public function update(array $data): Order
    {
        $this->order->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->order->save();
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
}
