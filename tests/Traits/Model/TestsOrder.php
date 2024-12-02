<?php

namespace Tests\Traits\Model;

use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\Payment;
use App\Models\Tour\Tour;
use App\Repository\RoomingRepository;
use Carbon\Carbon;

trait TestsOrder
{
    use TestsTour;

    function generateOrder(bool $withLead = true, bool $withIncluded = true, float $tour_cost = 300, float $surcharge = 50, float $deposit = 0): Order
    {
        if ($withIncluded) {
            $tour = $this->generateTour(false, ['base_price_per_person' => $tour_cost,]);
            for ($x = 0; $x < 5; $x++) {
                $this->generateAccommodationInventoryTour($tour, 'Included', 100, $this->generateAccommodationInventory(null, $this->generateRoomType(2), null, ['check_in' => now()->addDays($x), 'check_out' => now()->addDays($x)]));
                $this->generateActivityInventoryTour($tour);
                $this->generateFlightInventoryTour($tour);
                $this->generateTransportInventoryTour($tour);
                $this->generateMerchandiseInventoryTour($tour);
            }
        } else {
            $tour = Tour::find(1) ?? $this->generateTour($withIncluded, ['base_price_per_person' => $tour_cost, 'single_occupancy_surcharge' => $surcharge, 'deposit' => $deposit]);
        }
        $order = Order::factory()->make(['tour_id' => $tour->id, 'deposit' => $deposit,]);
        $order->saveQuietly();
        if ($withLead) $order->lead_booker_id = $this->generateOrderCustomer($withIncluded, $order, $tour_cost, $surcharge)->id;
        $order->saveQuietly();
        $order->repository->refresh();
        $order->repository->refresh();
        return $order;
    }

    function generatePayment(?Order $order, float $amount): Payment
    {
        if (!isset($order)) $order = $this->generateOrder(false);
        $payment = new Payment(['amount' => $amount, 'customer_id' => 1, 'payment_method_id' => 1, 'paid_on' => now(),]);
        $order->payments()->save($payment);
        return $payment;
    }

    function generateOrderCustomer(bool $withIncluded = false, ?Order $order = null, float $tour_cost = 300, float $surcharge = 50): OrderCustomer
    {
        if (!isset($order)) $order = $this->generateOrder();

        $orderCustomer = OrderCustomer::factory()->make(['tour_cost' => $tour_cost, 'single_occupancy_surcharge' => $surcharge,]);
        $order->orderCustomers()->save($orderCustomer);

        RoomingRepository::assignDefaultRooming($orderCustomer);
        $withIncluded && $orderCustomer->repository->addAllIncluded(true);

        if ($withIncluded) {
            foreach ($order->tour->merchandise()->where('tour_component_type', '=', 'Included')->get() as $component) {
                $component->repository->grantToCustomer($orderCustomer, true);
            }
        }

        $order->repository->refresh();

        return $orderCustomer;
    }

    function generateOrderInstallment(Carbon $date, float $amount, ?Order $order = null): OrderInstallment
    {
        if (!isset($order)) $order = $this->generateOrder();

        $installment = new OrderInstallment([
            'amount' => $amount,
            'due_on' => $date,
        ]);
        $order->installments()->save($installment);

        return $installment;
    }

    function generateManualAdjustment(float $amount, ?Order $order = null): ManualAdjustment
    {
        if (!isset($order)) $order = $this->generateOrder();

        $adjustment = new ManualAdjustment([
            'amount' => $amount,
            'date' => Carbon::now(),
            'reason' => 'Test Reason'
        ]);
        $order->adjustments()->save($adjustment);

        return $adjustment;
    }

    function generateOrderCustomerAdjustment(float $amount, Order|OrderCustomer|null $model = null): OrderCustomerAdjustment
    {
        if ($model instanceof OrderCustomer) $orderCustomer = $model;
        elseif ($model instanceof Order) $orderCustomer = $model->leadBooker;
        else $orderCustomer = $this->generateOrder()->leadBooker;

        $adjustment = new OrderCustomerAdjustment([
            'amount' => $amount,
            'date' => Carbon::now(),
            'reason' => 'Test Reason'
        ]);
        $orderCustomer->adjustments()->save($adjustment);

        return $adjustment;
    }

    function getDefaultCost(Order $order): float
    {
        $order->refresh();
        $order->repository->refresh();
        $cost = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            $cost += $orderCustomer->tour_cost + ($orderCustomer->has_surcharge ? $orderCustomer->single_occupancy_surcharge : 0);
        }
        return $cost;
    }

    function logComponents(OrderCustomer $orderCustomer)
    {
        $string = "";
        foreach ($orderCustomer->orderAccommodation()->with('tourComponent')->get() as  $component)
        {
            $string .= ("Accommodation({$component->tourComponent->id} - {$component->id}): {$component->tourComponent->tour_component_type} ($component->cost)\n");
            print_r($component->tourComponent->inventory);
        }
        foreach ($orderCustomer->orderActivities()->with('tourComponent')->get() as  $component)
        {
            $string .= ("Activity({$component->tourComponent->id} - {$component->id}): {$component->tourComponent->tour_component_type} ($component->cost)\n");
        }
        foreach ($orderCustomer->orderFlights()->with('tourComponent')->get() as  $component)
        {
            $string .= ("Flight({$component->tourComponent->id} - {$component->id}): {$component->tourComponent->tour_component_type} ($component->cost)\n");
        }
        foreach ($orderCustomer->orderTransports()->with('tourComponent')->get() as  $component)
        {
            $string .= ("Transport({$component->tourComponent->id} - {$component->id}): {$component->tourComponent->tour_component_type} ($component->cost)\n");
        }
        foreach ($orderCustomer->orderMerchandise()->with('tourComponent')->get() as  $component)
        {
            $string .= ("Merchandise({$component->tourComponent->id} - {$component->id}): {$component->tourComponent->tour_component_type} ($component->cost)\n");
        }
        return empty($string) ? "No Components" : $string;
    }
}
