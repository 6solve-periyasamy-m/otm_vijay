<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrdersCustomer;
use Illuminate\Support\Facades\DB;

interface OrderRepositoryInterface {
    public static function getSearchOrders($searchTerm = "", $archived = false);
    public static function getOrderDetails($orderId);
    public static function getOrderCustomerDetails($id);

}

class OrderRepository implements OrderRepositoryInterface
{
    public static $addonId = 2;

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

    public static function getOrderDetails($orderId)
    {
        $order = Order::findOrFail($orderId);
        $details = ['order' => $order,];
        $customers = [];
        $addons = [];
        $totalOrderValue = $order->total_order_value;
        $customerAdjustments = [];
        foreach ($order->orderCustomers as $customer) {
            $customers[] = $customer;
            // Possible TODO: Find a more elegant way to do this?
            foreach ($customer->orderAccommodation as $orderAccommodation) {
                if ($orderAccommodation->accommodationInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderAccommodation->accommodationInventoryTour;
                    $totalOrderValue += $orderAccommodation->accommodationInventoryTour->sales_price;
                }
            }
            foreach ($customer->orderActivities as $orderActivity) {
                if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderActivity->activityInventoryTour;
                    $totalOrderValue += $orderActivity->activityInventoryTour->sales_price;
                }
            }
            foreach ($customer->orderFlights as $orderFlight) {
                if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderFlight->flightInventoryTour;
                    $totalOrderValue += $orderFlight->flightInventoryTour->sales_price;
                }
            }
            foreach ($customer->orderTransports as $orderTransport) {
                if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderTransport->transportInventoryTour;
                    $totalOrderValue += $orderTransport->transportInventoryTour->sales_price;
                }
            }
            foreach ($customer->adjustments as $adjustment) {
                $totalOrderValue += $adjustment->amount;
            }
        }
        foreach ($order->adjustments as $adjustment) {
            $totalOrderValue += $adjustment->amount;
        }
        $details['customers'] = $customers;
        $details['addons'] = $addons;
        $details['totalOrderValue'] = $totalOrderValue;
        $payments = [];
        $totalPaid = 0;
        foreach ($order->payments as $payment) {
            $payments[] = $payment;
            $totalPaid += $payment->amount;
        }

        $details['totalPaid'] = $totalPaid;
        $details['payments'] = $payments;
        return $details;
    }

    public static function getOrderCustomerDetails($id)
    {
        $orderCustomer = OrdersCustomer::findOrFail($id);
        $details = ['order-customer' => $orderCustomer, 'customer' => $orderCustomer->customer, 'order' => $orderCustomer->order,];
        $accommodationArr = [];
        foreach ($orderCustomer->orderAccommodation as $orderAccommodation) {
            $data = [];
            $data['order'] = $orderAccommodation;
            $data['tour'] = $orderAccommodation->accommodationInventoryTour;
            $data['inventory'] = $orderAccommodation->accommodationInventory;
            $data['component'] = $orderAccommodation->accommodation;
            $accommodationArr[] = $data;
        }
        $details['accommodation'] = $accommodationArr;

        $activities = [];
        foreach ($orderCustomer->orderActivities as $orderActivity) {
            $data = [];
            $data['order'] = $orderActivity;
            $data['tour'] = $orderActivity->activityInventoryTour;
            $data['inventory'] = $orderActivity->activityInventory;
            $data['component'] = $orderActivity->activity;
            $activities[] = $data;
        }
        $details['activities'] = $activities;

        $flights = [];
        foreach ($orderCustomer->orderflights as $orderFlight) {
            $data = [];
            $data['order'] = $orderFlight;
            $data['tour'] = $orderFlight->flightInventoryTour;
            $data['inventory'] = $orderFlight->flightInventory;
            $data['component'] = $orderFlight->flight;
            $flights[] = $data;
        }
        $details['flights'] = $flights;

        $transports = [];
        foreach ($orderCustomer->ordertransports as $orderTransport) {
            $data = [];
            $data['order'] = $orderTransport;
            $data['tour'] = $orderTransport->transportInventoryTour;
            $data['inventory'] = $orderTransport->transportInventory;
            $data['component'] = $orderTransport->transport;
            $transports[] = $data;
        }
        $details['transports'] = $transports;

        return $details;
    }
}
