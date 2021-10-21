<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrdersAccommodation;
use App\Models\OrdersActivity;
use App\Models\OrdersCustomer;
use App\Models\OrdersFlight;
use App\Models\OrdersTransport;
use Illuminate\Support\Facades\DB;

interface OrderRepositoryInterface {
    public static function getSearchOrders($searchTerm = "", $archived = false);
    public static function getOrderDetails(Order $order);
    public static function getOrderCustomerDetails(OrdersCustomer $orderCustomer);
    public static function addIncludedToCustomer(OrdersCustomer $ordersCustomer, Order $order);

}

class OrderRepository implements OrderRepositoryInterface
{
    public static $addonId = "Add-on";

    public static function getSearchOrders($searchTerm = "", $archived = false)
    {
        $query = DB::table('orders')
            ->join('orders_customers AS orders_customers_details', 'orders_customers_details.order_id', '=', 'orders.id')
            ->join('orders_customers AS lead_booker', 'orders.lead_booker_id', '=', 'lead_booker.id')
            ->join('customers AS customer_details', 'orders_customers_details.customer_id', '=', 'customer_details.id')
            ->join('customers AS lead_booker_details', 'lead_booker.customer_id', '=', 'lead_booker_details.id')
            ->join('tours', 'orders.tour_id', '=', 'tours.id')
            ->where(function ($intQuery) use ($searchTerm) {
                $intQuery->where('customer_details.first_name', 'like', '%' . $searchTerm . '%')
                    ->OrWhere('customer_details.last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tours.title', 'like', '%' . $searchTerm . '%');
            });
        if (!$archived) $query->whereNull('orders.deleted_at');
        $query->select('orders.id AS order_id', 'tours.title AS tour_title', 'lead_booker.id AS lead_booker_id',
            'orders.booking_reference AS booking_reference', 'lead_booker_details.first_name AS lead_booker_first_name',
            'lead_booker_details.last_name AS lead_booker_last_name', 'orders.ordered_on AS ordered_on')
            ->groupBy('orders.id', 'tours.title', 'lead_booker.id', 'booking_reference',
                'lead_booker_details.first_name', 'lead_booker_details.last_name', 'orders.ordered_on')
            ->orderBy('ordered_on');
        return $query->get();
    }

    public static function getOrderDetails(Order $order)
    {
        $details = ['order' => $order,];
        $customers = [];
        $addons = [];
        $totalOrderValue = 0;
        $customerAdjustments = [];
        foreach ($order->orderCustomers as $customer) {
            $customers[] = $customer;
            // Possible TODO: Find a more elegant way to do this?
            foreach ($customer->orderAccommodation as $orderAccommodation) {
                if ($orderAccommodation->accommodationInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderAccommodation->accommodationInventoryTour;
                    $totalOrderValue += $orderAccommodation->accommodationInventoryTour->tour_sales_price;
                }
            }
            foreach ($customer->orderActivities as $orderActivity) {
                if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderActivity->activityInventoryTour;
                    $totalOrderValue += $orderActivity->activityInventoryTour->tour_sales_price;
                }
            }
            foreach ($customer->orderFlights as $orderFlight) {
                if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderFlight->flightInventoryTour;
                    $totalOrderValue += $orderFlight->flightInventoryTour->tour_sales_price;
                }
            }
            foreach ($customer->orderTransports as $orderTransport) {
                if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$addonId) {
                    $addons[] = $orderTransport->transportInventoryTour;
                    $totalOrderValue += $orderTransport->transportInventoryTour->tour_sales_price;
                }
            }
            foreach ($customer->adjustments as $adjustment) {
                $totalOrderValue += $adjustment->amount;
            }
        }
        foreach ($order->adjustments as $adjustment) {
            $totalOrderValue += $adjustment->amount;
        }
        $totalOrderValue += $order->tour->base_price_per_person * count($customers);
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

    public static function getOrderCustomerDetails(OrdersCustomer $orderCustomer)
    {
        $details = ['order_customer' => $orderCustomer, 'customer' => $orderCustomer->customer, 'order' => $orderCustomer->order,];
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


    public static function addIncludedToCustomer(OrdersCustomer $ordersCustomer, Order $order) {
        foreach ($order->tour->accommodationInventoryTours as $inventoryTour) {
            $orderInventory = OrdersAccommodation::make(['accommodation_inventory_tour_id' => $inventoryTour->id,]);
            $ordersCustomer->orderAccommodation()->save($orderInventory);
        }
        foreach ($order->tour->activityInventoryTours as $inventoryTour) {
            $orderInventory = OrdersActivity::make(['activity_inventory_tour_id' => $inventoryTour->id,]);
            $ordersCustomer->orderActivities()->save($orderInventory);
        }
        foreach ($order->tour->accommodationInventoryTours as $inventoryTour) {
            $orderInventory = OrdersFlight::make(['flight_inventory_tour_id' => $inventoryTour->id,]);
            $ordersCustomer->orderFlights()->save($orderInventory);
        }
        foreach ($order->tour->accommodationInventoryTours as $inventoryTour) {
            $orderInventory = OrdersTransport::make(['transport_inventory_tour_id' => $inventoryTour->id,]);
            $ordersCustomer->orderTransports()->save($orderInventory);
        }
    }
}
