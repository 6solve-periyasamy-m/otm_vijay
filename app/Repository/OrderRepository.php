<?php

namespace App\Repository;

use App\Mail\PaymentDueMailable;
use App\Models\Customer;
use App\Models\Merchandise;
use App\Models\Order;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\OrderInstallment;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use App\Models\PaymentInstallment;
use App\Models\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderRepository
{
    public static $addonId = "Add-on";
    public static $upgradeId = "Upgrade";

    // Page Details

    /**
     * Returns a list of orders that have been filtered
     * @param string $searchTerm Filter for searching the orders
     * @param false $archived Whether to show soft-deleted orders or not
     * @return Collection List of Orders, filtered using the filter
     */
    public static function getSearchOrders(string $searchTerm = "", bool $archived = false): Collection
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

    /**
     * Returns the details used by the Invoice view screen
     * @param Order $order
     * @return Order[] The invoice details
     */
    public static function getInvoiceDetails(Order $order): array
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
                        $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['quantity'] = 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['trans' . $tourInventory->id]['cost'] = $tourInventory->tour_sales_price;
                    }
                    $data['orderCustomers'][$orderCustomer->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['cost'] + $tourInventory->tour_sales_price;
                } else {
                    $included .= $tourInventory->transportInventory->transport->departureAddress->name . ' to ' .
                        $tourInventory->transportInventory->transport->arrivalAddress->name . ' - ' .
                        $tourInventory->transportInventory->transport->transportType->name . ' - ' .
                        $tourInventory->transportInventory->travelClass->name . "\n";
                }
            }
            foreach ($orderCustomer->orderMerchandise as $orderInventory) {
                $merchandise = $orderInventory->merchandise;
                if ($merchandise->tour_component_type !== "Included") {
                    if (isset($data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id])) {
                        $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['quantity'] = $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['quantity'] + 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['cost'] + $merchandise->tour_sales_price;
                    } else {
                        $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id] = [];
                        $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['description'] = $merchandise->name;
                        $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['quantity'] = 1;
                        $data['orderCustomers'][$orderCustomer->id]['items']['merch' . $merchandise->id]['cost'] = $merchandise->tour_sales_price;
                    }
                    $data['orderCustomers'][$orderCustomer->id]['cost'] = $data['orderCustomers'][$orderCustomer->id]['cost'] + $merchandise->tour_sales_price;
                } else {
                    $included .= $merchandise->name . "\n";
                }
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

    // Order Addons/Upgrades

    /**
     * Get all addons and upgrades for an order
     * @param Order $order
     * @return array{addons:array, upgrades:array, additionalValue:float} List of all addons, upgrades, and how much they come to total
     */
    public static function getOrderAdditionals(Order $order): array
    {
        $addons = [];
        $upgrades = [];
        $additionalValue = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            $data = self::getCustomerAdditionals($orderCustomer);
            $addons = array_merge($addons, $data['addons']);
            $upgrades = array_merge($upgrades, $data['upgrades']);
            $additionalValue += $data['additionalValue'];
        }
        return ['upgrades' => $upgrades, 'addons' => $addons, 'additionalValue' => $additionalValue,];
    }

    /**
     * Get the addons and upgrades for a specific customer
     * @param OrderCustomer $customer
     * @return array{addons:array,upgrades:array,additionalValue:float} The list of upgrades, addons and the sum of their costs
     */
    public static function getCustomerAdditionals(OrderCustomer $customer): array
    {
        $upgrades = [];
        $addons = [];
        $additionalValue = 0;
        foreach ($customer->orderAccommodation as $orderAccommodation) {
            if ($orderAccommodation->accommodationInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderAccommodation->accommodationInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderAccommodation->accommodationInventoryTour->tour_sales_price;
            }
            if ($orderAccommodation->accommodationInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderAccommodation->accommodationInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderAccommodation->accommodationInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderActivities as $orderActivity) {
            if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderActivity->activityInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderActivity->activityInventoryTour->tour_sales_price;
            }
            if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderActivity->activityInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderActivity->activityInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderFlights as $orderFlight) {
            if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderFlight->flightInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderFlight->flightInventoryTour->tour_sales_price;
            }
            if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderFlight->flightInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderFlight->flightInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderTransports as $orderTransport) {
            if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderTransport->transportInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderTransport->transportInventoryTour->tour_sales_price;
            }
            if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderTransport->transportInventoryTour, 'customer' => $customer,];
                $additionalValue += $orderTransport->transportInventoryTour->tour_sales_price;
            }
        }
        foreach ($customer->orderMerchandise as $orderMerchandise) {
            if ($orderMerchandise->merchandise->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderMerchandise->merchandise, 'customer' => $customer,];
                $additionalValue += $orderMerchandise->merchandise->tour_sales_price;
            }
        }
        return ['addons' => $addons, 'upgrades' => $upgrades, 'additionalValue' => $additionalValue,];
    }

    // Order/Customer Adjustments

    /**
     * Get the sum of the Order Adjustments
     * @param Order $order
     * @return float The sum of the order adjustments
     */
    public static function getOrderAdjustmentTotal(Order $order): float
    {
        $value = 0;
        foreach ($order->adjustments as $adjustment) {
            $value += $adjustment->amount;
        }
        return $value;
    }

    /**
     * Get the sum of the Customer Adjustments
     * @param Order $order
     * @return float The sum of the customer adjustments
     */
    public static function getCustomerAdjustmentTotal(Order $order): float
    {
        $total = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            foreach ($orderCustomer->adjustments as $adjustment) {
                $total += $adjustment->amount;
            }
        }
        return $total;
    }

    /**
     * Get the sum of the customer and order adjustments
     * @param Order $order
     * @return float Sum of the two adjustment values
     */
    public static function getTotalAdjustedValue(Order $order): float
    {
        return self::getCustomerAdjustmentTotal($order) + self::getOrderAdjustmentTotal($order);
    }

    // Order Costs

    /**
     * Get the current status of the order
     * @param Order $order
     * @return int Status code for order
     */
    public static function getOrderStatus(Order $order): int
    {
        $paidAmount = self::getPayments($order)['amount'];
        $cost = self::getCost($order);
        $adjustments = self::getTotalAdjustedValue($order);
        $total = $cost + $adjustments;
        if ($order->trashed() || $order->cancelled) {
            if ($paidAmount == 0) {
                return -3;
            } else if ($paidAmount <= $order->deposit) {
                return -2;
            } else {
                return -1;
            }
        } else {
            if ($total > $paidAmount) {
                $next = self::getNextPaymentDetails($order);
                if (isset($next['installment']) && Carbon::now()->isAfter($next['due'])) {
                    return 2;
                } else {
                    return 1;
                }
            } elseif ($total < $paidAmount) {
                return 3;
            } else {
                return 0;
            }
        }
    }

    /**
     * Get a breakdown of all the costs of the order
     * @param Order $order
     * @return array{customers:array,deposit:float,total:float}
     */
    public static function getCostBreakdown(Order $order): array
    {
        $breakdown = [];
        $total = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            $customerValue = $orderCustomer->tour_cost;
            $customerData = [];
            $data = self::getCustomerAdditionals($orderCustomer);
            $customerData['upgrades'] = $data['upgrades'];
            $customerData['addons'] = $data['addons'];
            $customerValue += $data['additionalValue'];
            $customerData['additionalValue'] = $customerValue;
            $total += $customerValue;
            $breakdown['customers'][] = $customerData;
        }
        $breakdown['deposit'] = $order->deposit;
        $breakdown['total'] = $total;
        return $breakdown;
    }

    /**
     * Get the amount the order has left to pay
     * @param Order $order
     * @return float The remaining amount required on the order
     */
    public static function getRemainingToPay(Order $order): float
    {
        $cost = $order->getCost();
        $paid = $order->getPaid();
        $adjustments = $order->getAdjustmentValue();
        return ($cost + $adjustments) - $paid;
    }

    /**
     * Get total cost amount for an order
     * @param Order $order
     * @return float The total cost of the order
     */
    public static function getCost(Order $order): float
    {
        return self::getCostBreakdown($order)['total'];
    }

    // Order Payments

    /**
     * Get details of all payments on an order
     * @param Order $order
     * @return array{payments:array, amount:float} List of payments, as well as the total amount paid
     */
    public static function getPayments(Order $order): array
    {
        $payments = [];
        $amount = 0;
        foreach ($order->payments as $payment) {
            $payments[] = $payment;
            $amount += $payment->amount;
        }
        return ['payments' => $payments, 'amount' => $amount,];
    }

    /**
     * Get the total value paid for an order
     * @param Order $order
     * @return float The amount paid by the customer
     */
    public static function getTotalPaid(Order $order): float
    {
        return self::getPayments($order)['amount'];
    }

    /**
     * Get details about the next payment
     * @param Order $order
     * @return array{amount:float,due:Carbon|null,installment:OrderInstallment|null} Details about the next installment. If installment is null, then no more installments are required
     */
    public static function getNextPaymentDetails(Order $order): array
    {
        $paid = self::getTotalPaid($order);
        $paid -= self::getOrderAdditionals($order)['additionalValue'];
        $paid -= self::getTotalAdjustedValue($order);
        $paid -= $order->deposit; // Deposit must be removed as it is an installment, but not treated as one (Celeste)
        foreach ($order->installments as $installment) {
            $paid -= ($installment->amount * $order->getCustomerCount());
            if ($paid < 0) {
                return [
                    'amount' => $installment->amount < $paid * -1 ? $installment->amount : $paid * -1,
                    'due' => $installment->due_on,
                    'installment' => $installment,
                ];
            }
        }
        return [
            'amount' => 0,
            'due' => null,
            'installment' => null,
        ];
    }

    // Order Management Methods

    /**
     * Adds the included components to an order-customer
     * @param OrderCustomer $orderCustomer
     */
    public static function addIncludedToCustomer(OrderCustomer $orderCustomer)
    {
        $order = $orderCustomer->order;
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
        foreach ($order->tour->merchandise as $merchandise) {
            if ($merchandise->tour_component_type === "Included") {
                $orderMerchandise = OrderMerchandise::make(['merchandise_id' => $merchandise->id,]);
                $orderCustomer->orderMerchandise()->save($orderMerchandise);
            }
        }
    }

    /**
     * Adds an Add-on Merchandise to an Order Customer
     * @param $oCustomerId
     * @param $merchandiseId
     * @return OrderMerchandise|null
     */
    public static function grantMerchandiseToCustomer(int $oCustomerId, int $merchandiseId): ?OrderMerchandise
    {
        $oCustomer = OrderCustomer::findOrFail($oCustomerId);
        Merchandise::findOrFail($merchandiseId);
        foreach ($oCustomer->orderMerchandise as $oMerch) {
            if ($oMerch->merchandise->id == $merchandiseId) return null;
        }
        $oMerch = OrderMerchandise::make(['merchandise_id' => $merchandiseId,]);
        $oCustomer->orderMerchandise()->save($oMerch);
        return $oMerch;
    }

    /**
     * Iterates through all orders, and if they have a due installment, sends an email reminder
     */
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

    /**
     * Sends an email reminder of a due installment
     * @param Order $order
     * @param PaymentInstallment $installment
     */
    public static function sendReminderEmail(Order $order, OrderInstallment $installment)
    {
        PaymentReminder::create([
            'order_id' => $order->id,
            'order_installment_id' => $installment->id,
        ]);
        Mail::to($order->leadBooker->email_address)->send(new PaymentDueMailable($order));
    }

    /**
     * Clone tour installments into order installments
     * @param Order $order
     */
    public static function cloneInstallments(Order $order) {
        foreach ($order->tour->paymentInstallments as $installment) {
            $oInstallment = OrderInstallment::make([
                'amount' => $installment->amount,
                'due_on' => $installment->due_on,
            ]);
            $order->installments()->save($oInstallment);
        }
    }

    // TODO: REWORK
    public static function getOrderFromBookingReference(string $bookingReference) : ?Order
    {
        return Order::where('booking_reference', '=', $bookingReference)->first();
    }

    public static function isLeadBooker(Order $order, Customer $customer) : bool {
        return $order->leadBooker->customer_id == $customer->id;
    }

    public static function isOrderCustomer(Order $order, Customer $customer) : bool {
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) return true;
        }
        return false;
    }

    public static function getCustomersForOrder(Order $order) {
        $customers = [];
        foreach ($order->orderCustomers as $orderCustomer) {
            $customers[] = $orderCustomer->customer;
        }
        return collect($customers);
    }

    public static function getCustomerInstallments(Order $order) {
        $paid = self::getTotalPaid($order);
        $paid -= $order->deposit;
        $paid -= self::getOrderAdditionals($order)['additionalValue'];
        $installments = [];
        $installments[] = ['name' => 'Deposit', 'due' => 'With Order',
            'status' => 'Paid in Full', 'color' => 'success',
            'total' => $order->deposit, 'remaining' =>
                $order->deposit, ];
        $installmentNumber = 1;
        foreach ($order->tour->paymentInstallments as $installment) {
            $paid -= ($installment->amount * $order->getCustomerCount());
            if ($paid <  0) {
                if (Carbon::now()->isAfter($installment->due_on)) {
                    $status = 'Payment Overdue';
                    $color = 'danger';
                } else {
                    $status = 'Balance Outstanding';
                    $color = 'warning';
                }
                $installmentDue = abs($paid);
                $paid = 0;
                $installmentPaid = $installment->amount - $installmentDue;
            } else {
                $status = 'Paid in Full';
                $color = 'success';
                $installmentDue = 0;
                $installmentPaid = $installment->amount;
            }
            $installments[] = ['name' => 'Installment ' . $installmentNumber, 'due' => $installment->due_on,
                'status' => $status, 'color' => $color, 'total' => $installment->amount, 'remaining' => $installmentDue, 'paid' => $installmentPaid];
        }
        return $installments;
    }

    public static function getCustomerOrderDetails(Customer $customer, Order $order)
    {
        if (!self::isLeadBooker($order, $customer)) abort(404);
        $data = [];
        $data['order'] = $order;
        $data['lead'] = $customer;
        $data['orderCustomers'] = $order->orderCustomers;
        $data['customers'] = $order->customers()->toArray();
        $data['payments'] = $order->payments;
        $data['status'] = $order->getStatus();
        $data['installments'] = self::getCustomerInstallments($order);
        $data['detail'] = self::getOrderDetails($order);
        return $data;
    }

    public static function getOrderDetails(Order $order)
    {
        $details = ['order' => $order,];
        $customers = $order->orderCustomers;
        $totalOrderValue = 0;
        $addonData = self::getOrderAdditionals($order);
        $totalOrderValue += $addonData['additionalValue'];
        $totalOrderValue += self::getOrderAdjustmentTotal($order);
        $totalOrderValue += self::getCustomerAdjustmentTotal($order);
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

    public static function getCustomerOrders(Customer $customer)
    {
        $data = [];
        foreach ($customer->orderCustomers as $orderCustomer) {
            $order = $orderCustomer->order;
            if (!self::isLeadBooker($order, $customer)) continue;
            $data[$order->booking_reference] = self::getCustomerOrderDetails($customer, $order);
        }

        return ['orders' => $data];
    }
}
