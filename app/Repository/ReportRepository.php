<?php

namespace App\Repository;

use App\Models\Order;

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
            $row->customer_count = OrderRepository::getOrderCustomerCount($order);
            $row->tour_name = $order->tour->name;
            $row->total_order_value = OrderRepository::getCosts($order);
            $row->balance_outstanding = OrderRepository::getCosts($order) - OrderRepository::getTotalPaid($order);
            $row->orderStatus = OrderRepository::getOrderStatus($order);
            $data[$order->id] = $row;
        }
        return $data;
    }
}
