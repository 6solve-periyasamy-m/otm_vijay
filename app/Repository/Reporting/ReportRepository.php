<?php /** @noinspection PhpDynamicFieldDeclarationInspection */

namespace App\Repository\Reporting;

use App\Helpers\RevenueHelper;
use App\Models\Booking\Booking;
use App\Models\Location\Address;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Order;
use App\Models\Tour\Tour;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Settings;

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
                'name' => 'Final Payments',
                'details' => 'Details about all final payments',
                'view' => 'reports.final-payment',
                'export' => 'reports.final-payment.export',
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
                'name' => 'Flight Details',
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
                'name' => 'Abandoned Bookings (Unknown Hidden)',
                'details' => 'List of all abandoned bookings where at least one contact detail is filled in',
                'view' => 'reports.abandoned-bookings-hidden',
                'export' => 'reports.abandoned-bookings-hidden.export',
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
            [
                'name' => 'Rooming',
                'details' => 'Information about all ordered rooms',
                'view' => 'reports.rooming',
                'export' => 'reports.rooming.export',
            ],
            [
                'name' => 'Rooming (No Notes)',
                'details' => 'Information about all ordered rooms, without the notes fields',
                'view' => 'reports.rooming',
                'export' => 'reports.rooming.export',
                'params' => ['notes' => false,],
            ],
            [
                'name' => 'Activity Manifest',
                'details' => 'Manifest of Ordered Activity Tickets',
                'view' => 'reports.manifest.activity.view',
                'export' => 'reports.manifest.activity.export',
            ],
            [
                'name' => 'Flight Manifest',
                'details' => 'Manifest of Ordered Flight Tickets',
                'view' => 'reports.manifest.flight.view',
                'export' => 'reports.manifest.flight.export',
            ],
            [
                'name' => 'Transport Manifest',
                'details' => 'Manifest of Ordered Transport Tickets',
                'view' => 'reports.manifest.transport.view',
                'export' => 'reports.manifest.transport.export',
            ],
            [
                'name' => 'Installment Revenue',
                'details' => 'Information about days revenue',
                'view' => 'reports.installment-revenue',
                'export' => 'reports.installment-revenue.export',
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
            $row->lb_first_name = $order->leadBooker?->customer?->first_name;
            $row->lb_last_name = $order->leadBooker?->customer?->last_name;
            $row->lb_email = $order->leadBooker?->customer?->email_address;
            $row->customer_count = $order->customer_count;
            $row->tour_name = $order->tour->name;
            $row->event_name = $order->tour->event?->name;
            $row->total_order_value = $order->total;
            $row->balance_outstanding = $order->remaining;
            $row->balance_paid = $order->paid;
            $row->due_date = $nextPayment?->due_on;
            $row->due_amount = $nextPayment?->calculated_amount;
            $row->internal_notes = $order->internal_notes;
            $row->external_notes = $order->external_notes;
            $row->orderStatus = $order->status;
            $data[$order->id] = $row;
        }
        return $data;
    }

    public static function getFinalPaymentReport(): array
    {
        $data = [];
        $orders = Order::with([
            'orderCustomers',
            'adjustments',
            'orderCustomers.adjustments',
            'orderCustomers.groups',
            'orderCustomers.orderAccommodation',
            'orderCustomers.orderActivities',
            'orderCustomers.orderFlights',
            'orderCustomers.orderTransports',
        ])->withSum('adjustments', 'amount')->withCount('orderCustomers', 'payingTravellers')->where('cancelled', '=', false)->get();
        foreach ($orders as $order) {
            $final = $order->repository->generateRemainingOrderInstallment();
            $row = collect();
            $row->ordered_on = $order->ordered_on;
            $row->booking_reference = $order->booking_reference;
            $row->lb_first_name = $order->leadBooker?->customer?->first_name;
            $row->lb_last_name = $order->leadBooker?->customer?->last_name;
            $row->lb_email = $order->leadBooker?->customer?->email_address;
            $row->customer_count = $order->customer_count;
            $row->tour_name = $order->tour->name;
            $row->event_name = $order->tour->event?->name;
            $row->total_order_value = $order->total;
            $row->balance_outstanding = $order->remaining;
            $row->balance_paid = $order->paid;
            $row->due = $order->tour->final_payment;
            $row->final_amount = $final?->calculated_amount;
            $row->final_paid = sigfig($final?->calculated_amount - $final->remaining);
            $row->final_remaining = $final->remaining;
            $data[] = $row;
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
            $row->event = $orderMerchandise->orderCustomer->order->tour->event?->name;
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
            $row->event = isset($tour->event) ? $tour->event?->name : 'No Event';
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
                $row->event_name = $order->tour->event?->name;
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
            $row->event = $orderFlight?->orderCustomer?->order?->tour?->event?->name;
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
            $row->activity_notes = $orderActivity?->orderCustomer?->activity_notes;
            $row->order_customer_notes_internal = $orderActivity?->orderCustomer?->internal_notes;
            $row->order_customer_notes_external = $orderActivity?->orderCustomer?->external_notes;
            $row->customer_notes_internal = $orderActivity?->orderCustomer?->customer?->internal_notes;
            $row->customer_notes_external = $orderActivity?->orderCustomer?->customer?->external_notes;
            $data[] = $row;
        }
        return $data;
    }

    public static function getAbandonedBookingsReport(int|null $limit = null, bool $hideUnknown = false): array
    {
        $data = [];
        $bookings = Booking::whereNull('order_id')->with(['tour', 'leadTraveller', 'leadTraveller.customer']);
        if ($limit !== null) {
            $bookings = $bookings->where('bookings.updated_at', '>', now()->subDays($limit));
        }
        if ($hideUnknown) {
            $bookings = $bookings
                ->join('booking_travellers as lt', 'lt.id', '=', 'bookings.lead_traveller_id')
                ->whereNotNull('lt.email_address')
                ->orWhereNotNull('lt.mobile_number')
                ->orWhereNotNull('lt.customer_id');
        }
        foreach ($bookings->get() as $booking) {
            $row = collect();
            $cDetailsSource = $booking->leadTraveller->customer ?? $booking->leadTraveller;
            if (empty($cDetailsSource->email_address) && empty($cDetailsSource->mobile_number)) { continue; }
            $row->name = $cDetailsSource?->title . ' ' . $cDetailsSource?->first_name . ' ' . $cDetailsSource?->last_name;
            $row->tour = $booking->tour?->name ?? 'Deleted Tour';
            $row->event = $booking->tour?->event?->name ?? 'No Event';
            $row->date = $booking->created_at;
            $row->travellers = $booking->travellers()->count();
            $row->expected = $booking->repository->getTotalCost();
            $row->contact_email = $cDetailsSource?->email_address ?? "Unknown";
            $row->contact_number = $cDetailsSource?->mobile_number ?? "Unknown";
            if (isset($booking->tour?->booking_form_url)) {
                $row->continue = $booking->tour?->getBookingFormUrl($booking, true);
            } else {
                $row->continue = "Booking URL not found for tour: " . ($booking->tour_id ?? "ID not set");
            }
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
                $row->reminded = $order->repository->hasBeenReminded($row->next, $max);
                $data[] = $row;
                $isFinal = $row->next->id == 0;
            }
            if ($order->repository->shouldRemindForFinal($max, $min) && !($isFinal ?? false)) {
                $final = $order->repository->generateRemainingOrderInstallment();
                $row = collect();
                $row->order = $order;
                $row->days = days_until($final->due_on);
                $row->next = $final;
                $row->reminded = $order->repository->hasBeenReminded($row->next, $max);
                $data[] = $row;
            }
        }
        return $data;
    }

    public static function generateAtolReport(Collection $orders): Collection
    {
        $passengers = 0;
        $revenue = 0;
        $paid = 0;
        $remaining = 0;
        $orderList = [];
        $filter = Settings::atolFilter();
        foreach ($orders as $order) {
            if (!$order->has_atol) continue;
            if ($filter !== -1 && $order->leadBooker->customer->homeAddress->country_id !== $filter) continue;
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

    public static function getInstallmentRevenueReport(): array
    {
        $data = [];
        foreach (RevenueHelper::getAllExpectedRevenue() as $key => $item) {
            $row = collect();
            $row->date = Carbon::createFromTimestamp($key);
            $row->amount = $item['count'];
            $row->expected = $item['expected'];
            $row->paid = $item['paid'];
            $data[] = $row;
        }
        return $data;
    }
}
