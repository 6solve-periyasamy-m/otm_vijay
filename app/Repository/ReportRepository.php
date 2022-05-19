<?php

namespace App\Repository;

use App\Helpers\QuarterHelper;
use App\Models\Order;
use App\Models\OrderFlight;
use App\Models\Tour;
use Illuminate\Support\Collection;

class ReportRepository
{
    /**
     * Get a list of available reports
     * @return array list of available reports
     */
    public static function getAvailableReports(): array
    {
        return [
            [
                'name' => 'Orders',
                'details' => 'Details about all orders, lead bookers, payments and the orders overall status',
                'view' => 'reports.order',
                'export' => 'reports.order.export',
            ],
            [
                'name' => 'Tour Stock',
                'details' => 'Details about all tours, and their available stock',
                'view' => 'reports.tour-stock',
                'export' => 'reports.tour-stock.export',
            ],
            [
                'name' => 'Payments',
                'details' => 'Details about all payments in the system',
                'view' => 'reports.payment',
                'export' => 'reports.payment.export',
            ],
            [
                'name' => 'Flight Manifest',
                'details' => 'List of all flights and passengers',
                'view' => 'reports.flight-manifest',
                'export' => 'reports.flight-manifest.export',
            ],
        ];
    }

    /**
     * Get a report of all orders
     * @return array List of orders and their data
     */
    public static function getOrderReport(): array
    {
        $data = [];
        foreach (Order::all() as $order) {
            $nextPayment = $order->getNextInstallment();
            $row = collect();
            $row->ordered_on = $order->ordered_on;
            $row->booking_reference = $order->booking_reference;
            $row->lb_first_name = $order->leadBooker->customer->first_name;
            $row->lb_last_name = $order->leadBooker->customer->last_name;
            $row->customer_count = $order->getCustomerCount();
            $row->tour_name = $order->tour->name;
            $row->total_order_value = $order->total;
            $row->balance_outstanding = $order->remaining;
            $row->balance_paid = $order->paid;
            $row->due_date = $nextPayment['due'];
            $row->due_amount = $nextPayment['amount'];
            $row->orderStatus = $order->getStatus();
            $data[$order->id] = $row;
        }
        return $data;
    }

    /**
     * Get a report of tour stock
     * @return array List of tours and their data
     */
    public static function getTourStockReport(): array {
        $data = [];
        foreach (Tour::all() as $tour) {
            $row = collect();
            $row->name = $tour->name;
            $row->event = isset($tour->event) ? $tour->event->name : 'No Event';
            $row->active = $tour->is_active;
            $row->stock = $tour->stock_control_active ? $tour->stock : 'Not Controlled';
            $row->booked = $tour->getUsedStock();
            $row->available = $tour->stock_control_active ? $tour->stock - $tour->getUsedStock() : 'Not Controlled';
            $row->percentage = $tour->stock_control_active ?
                ($tour->stock == 0 ? 100 : round(($tour->getUsedStock() / $tour->stock)*100, 2)) . '%' : 'Not Controlled';
            $row->notes = $tour->notes;
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Get a report of all payments on the system
     * @return array
     */
    public static function getPaymentReport(): array {
        $data = [];
        foreach (Order::with('payments', 'leadBooker', 'tour')->get() as $order) {
            foreach ($order->payments as $payment) {
                $row = collect();
                $row->booking_reference = $order->booking_reference;
                $row->tour_name = $order->tour->name;
                $row->lb_first_name = $order->leadBooker->customer->first_name;
                $row->lb_last_name = $order->leadBooker->customer->last_name;
                $row->payment_method = $payment->paymentMethod->name;
                $row->payment_type = $payment->payment_type;
                $row->amount = $payment->amount;
                $row->paid_on = $payment->paid_on;
                $data[] = $row;
            }
        }
        return $data;
    }

    public static function getFlightManifestReport(): array
    {
        $data = [];
        foreach (OrderFlight::all() as $orderFlight) {
            if ($orderFlight->isCancelled()) {
                continue;
            }
            $row = collect();
            $row->departs = $orderFlight?->tourComponent?->flightInventory?->flight?->departureAirport?->__toString();
            $row->depart_time = $orderFlight?->tourComponent?->flightInventory?->departs_at;
            $row->arrival = $orderFlight?->tourComponent?->flightInventory?->flight?->arrivalAirport?->__toString();
            $row->arrive_time = $orderFlight?->tourComponent?->flightInventory?->arrives_at;
            $row->flight_number = $orderFlight?->tourComponent?->flightInventory?->flight_number;
            $row->customer = $orderFlight?->orderCustomer?->customer_name;
            $row->reference = $orderFlight?->orderCustomer?->order?->booking_reference;
            $row->tour = $orderFlight?->orderCustomer?->order?->tour?->name;
            $row->is_lead = $orderFlight?->orderCustomer?->is_lead_booker;
            $row->flight_notes = $orderFlight?->orderCustomer?->flight_notes;
            $row->order_customer_notes_internal = $orderFlight?->orderCustomer?->internal_notes;
            $row->order_customer_notes_external = $orderFlight?->orderCustomer?->external_notes;
            $row->customer_notes_internal = $orderFlight?->orderCustomer?->customer?->internal_notes;
            $row->customer_notes_external = $orderFlight?->orderCustomer?->customer?->external_notes;
            $data[] = $row;
        }
        return $data;
    }

    public static function getOrdersPlacedInQuarterReport(int $year, int $quarter): Collection
    {
        return self::generateAtolReport(QuarterHelper::getOrdersPlacedInQuarter($year, $quarter));
    }

    public static function getOrdersDepartingInQuarterReport(int $year, int $quarter): Collection
    {
        return self::generateAtolReport(QuarterHelper::getOrdersFromToursInQuarter($year, $quarter));
    }

    public static function getOrdersDepartingAfterQuarterReport(int $year, int $quarter): Collection
    {
        return self::generateAtolReport(QuarterHelper::getOrdersFromToursAfterQuarter($year, $quarter));
    }

    public static function generateAtolReport(Collection $orders): Collection
    {
        $passengers = 0;
        $revenue = 0;
        $paid = 0;
        $remaining = 0;
        $orderList = [];
        foreach ($orders as $order) {
            if (!$order->has_atol_certificate) continue;
            if (!$order->cancelled) {
                $passengers += $order->getCustomerCount();
            }
            $revenue += $order->total;
            $paid += $order->paid;
            $remaining += $order->remaining;
            $orderList[] = $order;
        }
        $collection = collect();
        $collection->passengers = $passengers;
        $collection->revenue = $revenue;
        $collection->paid = $paid;
        $collection->remaining = $remaining;
        $collection->orders = $orderList;
        return $collection;
    }
}
