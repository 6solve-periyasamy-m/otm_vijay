<?php

namespace App\Repository;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

interface OrderRepositoryInterface {
    public static function getSearchOrders($searchTerm = "", $archived = false);

}

class OrderRepository implements OrderRepositoryInterface
{

    public static function getSearchOrders($searchTerm = "", $archived = false)
    {
        $query = DB::table('orders')
            ->join('tours', 'tours.id', '=', 'orders.tour_id')
            ->join('orders_customers', 'orders.id', '=', 'orders_customers.order_id')
            ->join('customers', 'customers.id', '=', 'orders_customers.customer_id')
            ->select('orders.id', 'tours.title', 'customers.first_name', 'customers.last_name', 'orders_customers.is_lead_booker', 'orders.booking_reference', 'orders.ordered_on')
            ->orderBy('orders.ordered_on', 'desc');
        if ($searchTerm === "") {
            $query->where('orders_customers.is_lead_booker', '=', true);
            if (!$archived) {
                $query->whereNull('orders.deleted_at');
            }
        } else {
            if ($archived) {
                $query->where(function ($intQuery) use ($searchTerm) {
                    $intQuery->where('customers.first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('customers.last_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('tours.title', 'like', '%' . $searchTerm . '%');
                });
            } else {
                $query->whereNull('orders.deleted_at')
                    ->where(function ($intQuery) use ($searchTerm) {
                        $intQuery->where('customers.first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('customers.last_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('tours.title', 'like', '%' . $searchTerm . '%');
                    });
            }
        }
        return $query->get();
    }
}