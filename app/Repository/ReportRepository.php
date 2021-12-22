<?php

namespace App\Repository;

use App\Models\Order;

interface ReportRepositoryInterface
{
    public static function getOrderReport();
}

class ReportRepository implements ReportRepositoryInterface
{
    public static function getOrderReport() {
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
