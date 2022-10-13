<?php

namespace App\Repository\Reporting;

use App\Helpers\QuarterHelper;
use App\Models\Booking\Booking;
use App\Models\Location\Address;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use App\Repository\Interfaces\HasRoomingList;
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
            [
                'name' => 'Activity Customer',
                'details' => 'List of all sold activities and tickets',
                'view' => 'reports.activities',
                'export' => 'reports.activities.export',
            ],
            [
                'name' => 'Abandoned Bookings',
                'details' => 'List of all abandoned bookings',
                'view' => 'reports.abandoned-bookings',
                'export' => 'reports.abandoned-bookings.export',
            ],
            [
                'name' => 'Order Reminders',
                'details' => 'Payments due to be reminded',
                'view' => 'reports.reminders',
                'export' => 'reports.reminders.export',
            ],
            [
                'name' => 'Merchandise Orders',
                'details' => 'Information and shipping details for Merchandise Orders',
                'view' => 'reports.merchandise',
                'export' => 'reports.merchandise.export',
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
            $nextPayment = $order->next_installment;
            $row = collect();
            $row->ordered_on = $order->ordered_on;
            $row->booking_reference = $order->booking_reference;
            $row->lb_first_name = $order->leadBooker->customer->first_name;
            $row->lb_last_name = $order->leadBooker->customer->last_name;
            $row->customer_count = $order->customer_count;
            $row->tour_name = $order->tour->name;
            $row->total_order_value = $order->total;
            $row->balance_outstanding = $order->remaining;
            $row->balance_paid = $order->paid;
            $row->due_date = $nextPayment?->due_on;
            $row->due_amount = $nextPayment?->amount;
            $row->orderStatus = $order->status;
            $data[$order->id] = $row;
        }
        return $data;
    }

    public static function getOrderMerchandiseReport(): array
    {
        $data = [];
        foreach (OrderMerchandise::with('orderCustomer', 'orderCustomer.order', 'orderCustomer.customer', 'orderCustomer.order.leadBooker', 'orderCustomer.order.tour', 'orderCustomer.order.leadBooker.customer', 'tourComponent', 'tourComponent.inventory', 'tourComponent.inventory.component')->get() as $orderMerchandise) {
            $tourComponent = $orderMerchandise->tourComponent; $inventory = $tourComponent->inventory; $component = $inventory->component;
            $row = collect();
            $row->id = $orderMerchandise->id;
            $row->name = "{$component->name} ({$component->type->name})";
            $row->variant = $inventory->variant->name;
            $row->size = $inventory->size->name;
            $row->tour = $orderMerchandise->orderCustomer->order->tour->name;
            $row->fulfilled = $orderMerchandise->fulfilled;
            $row->fulfil_route = route('merchandise.inventory.tour.order.fulfil', ['order' => $orderMerchandise->orderCustomer->order, 'orderCustomer' => $orderMerchandise->orderCustomer, 'orderMerchandise' => $orderMerchandise]);
            $row->customer = $orderMerchandise->orderCustomer->customer->full_name;
            $row->has_address = isset($orderMerchandise->orderCustomer->customer->homeAddress);
            $row->address = self::getShippingAddressForMerchandise($orderMerchandise->orderCustomer->customer->homeAddress, $orderMerchandise->orderCustomer->order->leadBooker->customer->homeAddress);
            $row->cost = $tourComponent->tour_component_type == 'Included' ? 0 : $orderMerchandise->cost;
            $row->ordered_on = $orderMerchandise->orderCustomer->order->ordered_on;
            $data[] = $row;
        }
        return $data;
    }

    private static function getShippingAddressForMerchandise(Address $customerAddress, Address $leadAddress): Address
    {
        return isset($customerAddress->address_line_1) && isset($customerAddress->country_id) && isset($customerAddress->postcode) ? $customerAddress : $leadAddress;
    }

    /**
     * Get a report of tour stock
     * @return array List of tours and their data
     */
    public static function getTourStockReport(): array
    {
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
                ($tour->stock == 0 ? 100 : round(($tour->getUsedStock() / $tour->stock) * 100, 2)) . '%' : 'Not Controlled';
            $row->notes = $tour->notes;
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Get a report of all payments on the system
     * @return array
     */
    public static function getPaymentReport(): array
    {
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
            if ($orderFlight->cancelled) {
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

    public static function getActivityReport(): array
    {
        $data = [];
        foreach (OrderActivity::all() as $orderActivity) {
            if ($orderActivity->cancelled) {
                continue;
            }
            $row = collect();
            $row->reference = $orderActivity?->orderCustomer?->order?->booking_reference;
            $row->customer = $orderActivity?->orderCustomer?->customer_name;
            $row->activity = $orderActivity?->tourComponent?->inventory?->activity?->name;
            $row->ticket = $orderActivity?->tourComponent?->inventory?->ticketType?->name;
            $row->starts = $orderActivity?->tourComponent?->inventory?->starts_at;
            $row->ends = $orderActivity?->tourComponent?->inventory?->ends_at;
            $row->purchased = $orderActivity?->orderCustomer?->order?->ordered_on;
            $row->cost = $orderActivity?->tourComponent?->tour_component_type === "Included" ? 0 : $orderActivity?->cost;
            $row->component = $orderActivity?->tourComponent?->tour_component_type;
            $data[] = $row;
        }
        return $data;
    }

    public static function getAbandonedBookingsReport(): array
    {
        $data = [];
        foreach (Booking::whereNull('order_id')->with('tour', 'leadTraveller', 'leadTraveller.customer')->get() as $booking) {
            $row = collect();
            $cDetailsSource = $booking->leadTraveller->customer ?? $booking->leadTraveller;
            $row->name = $cDetailsSource->title . ' ' . $cDetailsSource->first_name . ' ' . $cDetailsSource->last_name;
            $row->tour = $booking->tour?->name ?? 'Deleted Tour';
            $row->date = $booking->created_at;
            $row->travellers = $booking->travellers()->count();
            $row->expected = $booking->repository->getTotalCost();
            $row->contact_email = $cDetailsSource->email_address;
            $row->contact_number = $cDetailsSource->mobile_number;
            $row->continue = route('customer-booking.summary', ['bookingUrl' => $booking->tour->booking_form_url, 'token' => $booking->token,]);
            $data[] = $row;
        }
        return $data;
    }

    public static function getRemindersReport($max = 7, $min = -1000): array
    {
        $data = [];
        foreach (Order::where('cancelled', false)->get() as $order) {
            if ($order->repository->shouldRemind($max, $min)) {
                $row = collect();
                $row->order = $order;
                $row->days = $order->days_until_next_payment;
                $row->next = $order->next_installment;
                $row->reminded = $order->repository->hasBeenReminded($row->next);
                $data[] = $row;
            }
        }
        return $data;
    }

    public static function getOrdersPlacedInQuarterReport(int $year, int $quarter): Collection
    {
        return self::generateAtolReport(QuarterHelper::getOrdersPlacedInQuarter($year, $quarter));
    }

    public static function generateAtolReport(Collection $orders): Collection
    {
        $passengers = 0;
        $revenue = 0;
        $paid = 0;
        $remaining = 0;
        $orderList = [];
        foreach ($orders as $order) {
            if (!$order->has_atol) continue;
            if (!$order->cancelled) {
                $passengers += $order->customer_count;
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

    public static function getOrdersDepartingInQuarterReport(int $year, int $quarter): Collection
    {
        return self::generateAtolReport(QuarterHelper::getOrdersFromToursInQuarter($year, $quarter));
    }

    public static function getOrdersDepartingAfterQuarterReport(int $year, int $quarter): Collection
    {
        return self::generateAtolReport(QuarterHelper::getOrdersFromToursAfterQuarter($year, $quarter));
    }
}
