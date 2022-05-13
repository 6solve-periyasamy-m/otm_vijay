<?php

namespace App\Repository\Model\Order;

use App\Models\Order\Order;
use App\Repository\Abstracts\ModelRepository;
use Illuminate\Support\Facades\DB;

class OrderRepository extends ModelRepository
{
    private Order $order;
    private AtolRepository $atolRepository;

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
        foreach ($this->order->groups() as $group) {
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
