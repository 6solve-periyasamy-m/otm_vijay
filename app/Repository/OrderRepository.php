<?php

namespace App\Repository;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\OrderTransport;
use App\Models\Tour;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

interface OrderRepositoryInterface
{
    public static function getSearchOrders($searchTerm = "", $archived = false);

    public static function getOrderDetails(Order $order);

    public static function getOrderCustomerDetails(OrderCustomer $orderCustomer);

    public static function addIncludedToCustomer(OrderCustomer $ordercustomer, Order $order);

    public static function getInvoiceDetails(Order $order);

}

class OrderRepository implements OrderRepositoryInterface
{
    public static $addonId = "Add-on";

    public static function getSearchOrders($searchTerm = "", $archived = false)
    {
        $query = DB::table('orders')
            ->join('order_customers AS order_customers_details', 'order_customers_details.order_id', '=', 'orders.id')
            ->join('order_customers AS lead_booker', 'orders.lead_booker_id', '=', 'lead_booker.id')
            ->join('customers AS customer_details', 'order_customers_details.customer_id', '=', 'customer_details.id')
            ->join('customers AS lead_booker_details', 'lead_booker.customer_id', '=', 'lead_booker_details.id')
            ->join('tours', 'orders.tour_id', '=', 'tours.id')
            ->where(function ($intQuery) use ($searchTerm) {
                $intQuery->where('customer_details.first_name', 'like', '%' . $searchTerm . '%')
                    ->OrWhere('customer_details.last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tours.name', 'like', '%' . $searchTerm . '%');
            });
        if (!$archived) $query->whereNull('orders.deleted_at');
        $query->select('orders.id AS order_id', 'tours.name AS tour_title', 'lead_booker.id AS lead_booker_id',
            'orders.booking_reference AS booking_reference', 'lead_booker_details.first_name AS lead_booker_first_name',
            'lead_booker_details.last_name AS lead_booker_last_name', 'orders.ordered_on AS ordered_on')
            ->groupBy('orders.id', 'tours.name', 'lead_booker.id', 'booking_reference',
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

    public static function getOrderCustomerDetails(OrderCustomer $orderCustomer)
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


    public static function addIncludedToCustomer(OrderCustomer $orderCustomer, Order $order)
    {
        foreach ($order->tour->accommodationInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderAccommodation::make(['accommodation_inventory_tour_id' => $inventoryTour->id,]);
                $orderCustomer->orderAccommodation()->save($orderInventory);
            }
        }
        foreach ($order->tour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderActivity::make(['activity_inventory_tour_id' => $inventoryTour->id,]);
                $orderCustomer->orderActivities()->save($orderInventory);
            }
        }
        foreach ($order->tour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderFlight::make(['flight_inventory_tour_id' => $inventoryTour->id,]);
                $orderCustomer->orderFlights()->save($orderInventory);
            }
        }
        foreach ($order->tour->transportInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderTransport::make(['transport_inventory_tour_id' => $inventoryTour->id,]);
                $orderCustomer->orderTransports()->save($orderInventory);
            }
        }
    }

    public static function getInvoiceDetails(Order $order)
    {
        $data = ['order' => $order,];
        $data['orderCustomers'] = [];
        $data['payments'] = [];
        $adjustments = [];
        $payments = [];
        $data['totals']['orderValue'] = 0;
        $data['totals']['paid'] = 0;
        $data['totals']['adjusted'] = 0;

        foreach ($order->orderCustomers as $orderCustomer) {
            $data['orderCustomers'][$orderCustomer->id] = [];
            $data['orderCustomers'][$orderCustomer->id]['customer'] = $orderCustomer;
            $data['orderCustomers'][$orderCustomer->id]['items'] = [];
            $data['orderCustomers'][$orderCustomer->id]['cost'] = $order->tour->base_price_per_person;
            $included = "";
            foreach ($orderCustomer->orderAccommodation as $orderInventory) {
                $tourInventory = $orderInventory->accommodationInventoryTour;
                if ($tourInventory->tour_component_type !== "Included") {
                    if (isset($data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id])) {
                        $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['quantity'] = $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['quantity'] + 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['cost'] + $tourInventory->tour_sales_price;
                    } else {
                        $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id] = [];
                        $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['description'] =
                            $tourInventory->accommodationInventory->accommodation->name . ' - ' .
                            $tourInventory->accommodationInventory->roomType->name . ' - ' .
                            $tourInventory->accommodationInventory->boardType->name;
                        $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['quantity'] = 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['accom' . $tourInventory->id]['cost'] = $tourInventory->tour_sales_price;
                    }
                    $data['orderCustomers'][$orderCustomer->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['cost'] + $tourInventory->tour_sales_price;
                } else {
                    $included .= $tourInventory->accommodationInventory->accommodation->name . ' - ' .
                        $tourInventory->accommodationInventory->roomType->name . ' - ' .
                        $tourInventory->accommodationInventory->boardType->name . "\n";
                }
            }
            foreach ($orderCustomer->orderActivities as $orderInventory) {
                $tourInventory = $orderInventory->activityInventoryTour;
                if ($tourInventory->tour_component_type !== "Included") {
                    if (isset($data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id])) {
                        $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['quantity'] = $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['quantity'] + 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['cost'] + $tourInventory->tour_sales_price;
                    } else {
                        $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id] = [];
                        $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['description'] =
                            $tourInventory->activityInventory->activity->name . ' - ' .
                            $tourInventory->activityInventory->activity->activityType->name . ' - ' .
                            $tourInventory->activityInventory->ticketType->name;
                        $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['quantity'] = 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['activ' . $tourInventory->id]['cost'] = $tourInventory->tour_sales_price;
                    }
                    $data['orderCustomers'][$orderCustomer->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['cost'] + $tourInventory->tour_sales_price;
                } else {
                    $included .= $tourInventory->activityInventory->activity->name . ' - ' .
                        $tourInventory->activityInventory->activity->activityType->name . ' - ' .
                        $tourInventory->activityInventory->ticketType->name . "\n";
                }
            }
            foreach ($orderCustomer->orderFlights as $orderInventory) {
                $tourInventory = $orderInventory->flightInventoryTour;
                if ($tourInventory->tour_component_type !== "Included") {
                    if (isset($data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id])) {
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['quantity'] = $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['quantity'] + 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['cost'] + $tourInventory->tour_sales_price;
                    } else {
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id] = [];
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['description'] =
                            $tourInventory->flightInventory->flight->departureAirport->name . ' to ' .
                            $tourInventory->flightInventory->flight->arrivalAirport->name . ' - ' .
                            $tourInventory->flightInventory->travelClass->name;
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['quantity'] = 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['cost'] = $tourInventory->tour_sales_price;
                    }
                    $data['orderCustomers'][$orderCustomer->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['cost'] + $tourInventory->tour_sales_price;
                } else {
                    $included .= $tourInventory->flightInventory->flight->departureAirport->name . ' to ' .
                        $tourInventory->flightInventory->flight->arrivalAirport->name . ' - ' .
                        $tourInventory->flightInventory->travelClass->name . "\n";
                }
            }
            foreach ($orderCustomer->orderTransports as $orderInventory) {
                $tourInventory = $orderInventory->transportInventoryTour;
                if ($tourInventory->tour_component_type !== "Included") {
                    if (isset($data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id])) {
                        $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['quantity'] = $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['quantity'] + 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['cost'] + $tourInventory->tour_sales_price;
                    } else {
                        $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id] = [];
                        $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['description'] =
                            $tourInventory->transportInventory->transport->departureAddress->name . ' to ' .
                            $tourInventory->transportInventory->transport->arrivalAddress->name . ' - ' .
                            $tourInventory->transportInventory->transport->transportType->name . ' - ' .
                            $tourInventory->transportInventory->travelClass->name;
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['quantity'] = 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['flight' . $tourInventory->id]['cost'] = $tourInventory->tour_sales_price;
                    }
                } else {
                    $included .= $tourInventory->transportInventory->transport->departureAddress->name . ' to ' .
                        $tourInventory->transportInventory->transport->arrivalAddress->name . ' - ' .
                        $tourInventory->transportInventory->transport->transportType->name . ' - ' .
                        $tourInventory->transportInventory->travelClass->name . "\n";
                }
                $data['orderCustomers'][$orderCustomer->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['cost'] + $tourInventory->tour_sales_price;
            }
            $data['totals']['orderValue'] += $data['orderCustomers'][$orderCustomer->id]['cost'];
            $data['orderCustomers'][$orderCustomer->id]['included'] = $included;
            foreach ($orderCustomer->adjustments as $adjustment) {
                $adjustments[] = ['date' => $adjustment->date, 'amount' => $adjustment->amount,
                    'reason' => 'Customer Adjustment (' . $orderCustomer->customer->first_name . ' ' . $orderCustomer->customer->last_name . '): ' . $adjustment->reason,];
                $data['totals']['adjusted'] += $adjustment->amount;
            }
        }

        foreach ($order->payments as $payment) {
            $payments[] = ['date' => $payment->paid_on, 'amount' => $payment->amount, 'method' => $payment->payment_type . ': ' . $payment->paymentMethod->name,];
            $data['totals']['paid'] += $payment->amount;
        }

        foreach ($order->adjustments as $adjustment) {
            $adjustments[] = ['date' => $adjustment->date, 'amount' => $adjustment->amount, 'reason' => 'Manual Adjustment: ' . $adjustment->reason,];
            $data['totals']['adjusted'] += $adjustment->amount;
        }

        $data['adjustments'] = collect($adjustments)->sortBy('date')->toArray();
        $data['payments'] = collect($payments)->sortBy('date')->toArray();
        $data['totals']['combined'] = $data['totals']['orderValue'] - $data['totals']['paid'] + $data['totals']['adjusted'];
        return $data;
    }

    /**
     * @param Order $order
     * @return array
     * @var Customer $customer
     * @var Tour $tour
     * @var Event $event
     */
    public static function getOrderShortCodes(Order $order = null)
    {
        $faker = Faker::create();
        $customer = isset($order) ? $order->leadBooker->customer : null;
        $tour = isset($order) ? $order->tour : null;
        $event = isset($order) ? $tour->event : null;
        $nextPayment = self::getNextPaymentDetails($order);
        $data = [
            'TITLE' => !isset($order) ? $faker->title : $customer->title,
            'FIRST_NAME' => !isset($order) ? $faker->firstName : $customer->first_name,
            'MIDDLE_NAMES' => !isset($order) ? $faker->firstName : $customer->middle_names,
            'LAST_NAME' => !isset($order) ? $faker->lastName : $customer->last_name,
            'PASSPORT_EXPIRY_DATE' => !isset($order) ? $faker->date : $customer->passport_expiry_date,
            'BOOKING_REFERENCE' => !isset($order) ? $faker->regexify('OTM[0-9]{12}[A-Z]{4}') : $order->booking_reference,
            'ORDERED_ON' => !isset($order) ? $faker->date : $order->ordered_on,
            'TOTAL_PAID' => !isset($order) ? $faker->numberBetween(100, 1000) : self::getTotalPaid($order),
            'PAYMENT_AMOUNT' => !isset($order) ? $faker->numberBetween(100, 1000) : $nextPayment['amount'],
            'PAYMENT_DUE' => !isset($order) ? $faker->date : $nextPayment['due'],
            'TOUR_NAME' => !isset($order) ? implode(' ', $faker->words) : $tour->name,
            'TOUR_DESCRIPTION' => !isset($order) ? $faker->sentence : $tour->description,
            'TOUR_START' => !isset($order) ? $faker->date : $tour->date_from,
            'TOUR_END' => !isset($order) ? $faker->date : $tour->date_to,
            'TOUR_BASE_PER_PERSON' => !isset($order) ? $faker->numberBetween(100, 1000) : $tour->base_price_per_person,
            'TOUR_DEPOSIT' => !isset($order) ? $faker->numberBetween(100, 1000) : $tour->deposit,
            'TOUR_SURCHARGE' => !isset($order) ? $faker->numberBetween(100, 1000) : $tour->single_occupancy_surcharge,
            'LATEST_INVOICE' => route('orders.invoice.latest', ['order' => $order,]), // TODO: Link to customers invoices
            'PORTAL_LINK' => route('customer.portal', ['customer' => $customer,]),
            'ATOL_LINK' => route('customer.atol', ['customer' => $customer,]),
            'DETAILS_LINK' => route('customer.details', ['customer' => $customer,]),
        ];

        return $data;
    }

    public static function getNextPaymentDetails(Order $order)
    {
        $paid = self::getTotalPaid($order);
        $paid -= $order->tour->deposit;
        foreach ($order->tour->paymentInstallments as $installment) {
            $paid -= $installment->amount;
            if ($paid < 0) {
                return [
                    'amount' => $paid * -1,
                    'due' => $installment->due_on
                ];
            }
        }
        return [
            'amount' => 0,
            'due' => $order->tour->date_from,
        ];
    }

    public static function getTotalPaid(Order $order)
    {
        $paid = 0;
        foreach ($order->payments as $payment) {
            $paid += $payment->amount;
        }
        return $paid;
    }

    public static function getCosts(Order $order)
    {

    }
}
