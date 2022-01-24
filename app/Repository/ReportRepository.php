<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\Tour;

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
            ]
        ];
    }

    /**
     * Get a report of all orders
     * @return array List of orders and their data
     */
    public static function getOrderReport(): array
    {
        $data = [];
        foreach (Order::withTrashed()->get() as $order) {
            $row = collect();
            $row->ordered_on = $order->ordered_on;
            $row->booking_reference = $order->booking_reference;
            $row->lb_first_name = $order->leadBooker->customer->first_name;
            $row->lb_last_name = $order->leadBooker->customer->last_name;
            $row->customer_count = $order->getCustomerCount();
            $row->tour_name = $order->tour->name;
            $row->total_order_value = $order->getCost();
            $row->balance_outstanding = $order->getCost() - OrderRepository::getTotalPaid($order);
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
            $row->stock = $tour->stock_control_active ? $tour->stock : 'Not Controlled';
            $row->booked = $tour->getUsedStock();
            $row->available = $tour->stock_control_active ? $tour->stock - $tour->getUsedStock() : 'Not Controlled';
            $row->percentage = $tour->stock_control_active ? round(($tour->getUsedStock() / $tour->stock)*100, 2) . '%' : 'Not Controlled';
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
}
