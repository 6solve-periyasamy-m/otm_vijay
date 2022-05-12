<?php

namespace App\Repository\Model;

use App\Models\Order\Order;

class OrderRepository
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public static function getFromBookingReference(string $reference): ?Order
    {
        return Order::whereBookingReference($reference)->first();
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getInvoiceRepository(int $number = 0): InvoiceRepository
    {
        return InvoiceRepository::getInvoiceById($this->order, $number);
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
            foreach ($orderCustomer->orderActivities as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
            foreach ($orderCustomer->orderFlights as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
            foreach ($orderCustomer->orderTransports as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
            foreach ($orderCustomer->orderMerchandise as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
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
}
