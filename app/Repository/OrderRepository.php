<?php

namespace App\Repository;

use App\Mail\PaymentDueMailable;
use App\Models\Order;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\OrderTransport;
use App\Models\PaymentInstallment;
use App\Models\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $customers = $order->orderCustomers;
        $totalOrderValue = 0;
        $addonData = self::getOrderAddons($order);
        $totalOrderValue += $addonData['additionalValue'];
        $totalOrderValue += self::getOrderAdjustmentTotal($order);
        $totalOrderValue += $order->tour->base_price_per_person * count($customers);
        $details['customers'] = $customers;
        $details['addons'] = $addonData['addons'];
        $details['totalOrderValue'] = $totalOrderValue;
        $paymentData = self::getPayments($order);
        $details['totalPaid'] = $paymentData['amount'];
        $details['payments'] = $paymentData['payments'];
        $details['orderStatus'] = self::getOrderStatus($order);
        $details['nextPayment'] = self::getNextPaymentDetails($order);
        return $details;
    }

    public static function getOrderAddons(Order $order): array
    {
        $addons = [];
        $additionalValue = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            $data = self::getCustomerAddons($orderCustomer);
            $addons = array_merge($addons, $data['addons']);
            $additionalValue += $data['additionalValue'];
        }
        return ['addons' => $addons, 'additionalValue' => $additionalValue,];
    }

    public static function getCustomerAddons(OrderCustomer $customer): array
    {
        $addons = [];
        $additionalValue = 0;
        foreach ($customer->orderAccommodation as $orderAccommodation) {
            if ($orderAccommodation->accommodationInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderAccommodation->accommodationInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderAccommodation->accommodationInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderActivities as $orderActivity) {
            if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderActivity->activityInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderActivity->activityInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderFlights as $orderFlight) {
            if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderFlight->flightInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderFlight->flightInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderTransports as $orderTransport) {
            if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderTransport->transportInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderTransport->transportInventoryTour->tour_sales_price;
            }
        }
        return ['addons' => $addons, 'additionalValue' => $additionalValue,];
    }

    public static function getOrderAdjustmentTotal(Order $order)
    {
        $value = 0;
        foreach ($order->adjustments as $adjustment) {
            $value += $adjustment->amount;
        }
        return $value;
    }

    public static function getPayments(Order $order)
    {
        $payments = [];
        $amount = 0;
        foreach ($order->payments as $payment) {
            $payments[] = $payment;
            $amount += $payment->amount;
        }
        return ['payments' => $payments, 'amount' => $amount,];
    }

    public static function getOrderStatus(Order $order)
    {
        $paidAmount = self::getPayments($order)['amount'];
        $cost = self::getCosts($order);
        $adjustments = self::getOrderAdjustmentTotal($order);
        if (($cost + $adjustments) > $paidAmount) {
            $next = self::getNextPaymentDetails($order);
            if (isset($next['installment']) && Carbon::now()->isAfter($next['due'])) {
                return ['status' => 'Payment Overdue', 'color' => 'danger'];
            } else {
                return ['status' => 'Balance Outstanding', 'color' => 'warning'];
            }
        } else {
            return ['status' => 'Paid in Full', 'color' => 'success'];
        }
    }

    public static function getCosts(Order $order)
    {
        $cost = $order->tour->base_price_per_person * self::getOrderCustomerCount($order);
        foreach (self::getOrderAddons($order)['addons'] as $addon) {
            $cost += $addon['addon']->tour_sales_price;
        }
        return $cost;
    }

    public static function getOrderCustomerCount(Order $order): int
    {
        return $order->orderCustomers()->count();
    }

    public static function getNextPaymentDetails(Order $order)
    {
        $paid = self::getTotalPaid($order);
        $paid -= self::getOrderDepositAmount($order);
        $paid -= self::getOrderAddons($order)['additionalValue'];
        foreach ($order->tour->paymentInstallments as $installment) {
            $paid -= ($installment->amount * self::getOrderCustomerCount($order));
            if ($paid < 0) {
                return [
                    'amount' => $paid * -1,
                    'due' => $installment->due_on,
                    'installment' => $installment,
                ];
            }
        }
        return [
            'amount' => 0,
            'due' => $order->tour->date_from,
            'installment' => null,
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
        $details['status'] = self::getOrderStatus($orderCustomer->order);
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

    public static function sendAllOrderReminders()
    {
        foreach (Order::all() as $order) {
            $nextPayment = self::getNextPaymentDetails($order);
            if (!isset($nextPayment['installment']) || Carbon::parse($nextPayment['due'])->diffInDays(now(), true) > 7) continue;
            $reminder = PaymentReminder::where('order_id', '=', $order->id)->andWhere('payment_installment_id', '=', $nextPayment['installment']->id)->first();
            if (isset($reminder)) continue;
            self::sendReminderEmail($order, $nextPayment['installment']->id);
        }
    }

    public static function sendReminderEmail(Order $order, PaymentInstallment $installment)
    {
        PaymentReminder::create([
            'order_id' => $order->id,
            'payment_installment_id' => $installment->id,
        ]);
        Mail::to($order->leadBooker->email_address)->send(new PaymentDueMailable($order));
    }

    public static function getOrderDepositAmount(Order $order)
    {
        return $order->tour->deposit * self::getOrderCustomerCount($order);
    }
}
