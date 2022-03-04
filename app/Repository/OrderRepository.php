<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Exceptions\RoomingFailedException;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Merchandise;
use App\Models\Order;
use App\Models\OrderAccommodation;
use App\Models\OrderActivity;
use App\Models\OrderCustomer;
use App\Models\OrderFlight;
use App\Models\OrderInstallment;
use App\Models\OrderMerchandise;
use App\Models\OrderTransport;
use App\Models\PaymentReminder;
use App\Models\Invoice;
use App\Models\RoomType;
use App\Models\Tour;
use App\Repository\Facades\StringFormatter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;
use mikehaertl\pdftk\Pdf;
use Storage;
use Throwable;
use ZipArchive;

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
            'lead_booker_details.last_name AS lead_booker_last_name', 'orders.ordered_on AS ordered_on', DB::raw('COUNT(order_customers_details.id) AS passenger_count'))
            ->groupBy('orders.id', 'tours.name', 'lead_booker.id', 'booking_reference',
                'lead_booker_details.first_name', 'lead_booker_details.last_name', 'orders.ordered_on')
            ->orderBy('ordered_on', 'DESC');
        return $query->get();
    }

    public static function generateInvoice(Order $order): Invoice
    {
        $customers = [];
        $groups = [];
        $adjustments = [];
        $payments = [];
        foreach ($order->orderCustomers as $customer) {
            $customers[$customer->customer_name] = self::processCustomerComponentsForInvoice($customer);
            foreach ($customer->adjustments as $adjustment) {
                $adjustments[] = ['description' => "Customer Adjustment ({$customer->customer_name}): {$adjustment->reason}", 'cost' => $adjustment->amount,];
            }
        }
        foreach ($order->groups() as $group) {
            $groups[$group->name] = self::processGroupComponentsForInvoice($group);
        }
        foreach ($order->adjustments as $adjustment) {
            $adjustments[] = ['description' => "Manual Adjustment: {$adjustment->reason}", 'cost' => $adjustment->amount,];
        }
        foreach ($order->payments as $payment) {
            $payments[] = ['date' => $payment->paid_on, 'description' => "{$payment->paymentMethod}: {$payment->payment_type}", 'cost' => $payment->amount,];
        }
        return Invoice::make([
            'order_id' => $order->id,
            'number' => $order->invoices->count() + 1,
            'generated' => now(),
            'customers' => $customers,
            'adjustments' => ['total_cost' => $order->getAdjustmentValue(), 'billables' => $adjustments,],
            'payments' => ['total_cost' => $order->paid, 'billables' => $payments,],
            'groups' => $groups,
            'installments' => self::snapshotInstallments($order),
            'footer' => $order->invoice_footer,
            'total_cost' => $order->getCost() + $order->getAdjustmentValue(),
        ]);
    }

    public static function saveInvoice(Order $order)
    {
        $invoice = self::generateInvoice($order);
        $invoice->save();
        return $invoice;
    }

    public static function snapshotInstallments(Order $order)
    {
        $data = [['due' => 'With Order',
            'description' => self::buildInstallmentString('Deposit', $order, $order->deposit, $order->calculated_deposit),
            'amount' => $order->calculated_deposit, 'paid' => $order->paid >= $order->calculated_deposit,]];
        foreach ($order->installments as $installment) {
            $data[] = ['due' => $installment->due_on, 'description' => self::buildInstallmentString('Installment', $order, $installment->amount, $installment->calculated_amount),
                'amount' => $installment->calculated_amount, 'paid' => $installment->paid,];
        }
        $data[] = ['due' => $order->tour->final_payment, 'description' => 'Remaining Balance: ' . \StringFormatter::formatCurrency($order->remaining_installment),
            'amount' => $order->remaining_installment, 'paid' => $order->paid >= $order->getCost(), ];
        return $data;
    }

    public static function buildInstallmentString(string $type, Order $order, float $amount, float $calculated): string
    {
        return $type . ': ' . $order->getCustomerCount() . ' Customer' . ($order->getCustomerCount() > 1 ? 's' : '') . ' x '
            . \StringFormatter::formatCurrency($amount) . ' = ' . \StringFormatter::formatCurrency($calculated);
    }

    private static function processCustomerComponentsForInvoice(OrderCustomer $orderCustomer): array
    {
        $data = [];
        $totalCost = 0;
        $included = "Base Components Include:\n";
        if ($orderCustomer->hasSurcharge) {
            $data[] = ['description' => 'Single Occupancy Surcharge', 'cost' => $orderCustomer->single_occupancy_surcharge,];
            $totalCost += $orderCustomer->single_occupancy_surcharge;
        }
        foreach ($orderCustomer->orderAccommodation() as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            }
        }
        foreach ($orderCustomer->orderActivities as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => '' . $tourInventory, 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        foreach ($orderCustomer->orderFlights as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => '' . $tourInventory, 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        foreach ($orderCustomer->orderTransports as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => '' . $tourInventory, 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        foreach ($orderCustomer->orderMerchandise as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type == 'Included') {
                $included .= $tourInventory . "\n";
            } else {
                $data[] = ['description' => '' . $tourInventory, 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        $totalCost += $orderCustomer->tour_cost;
        return ['total_cost' => $totalCost, 'billables' => array_merge([['description' => $included, 'cost' => $orderCustomer->tour_cost,]], $data),];
    }

    /**
     * @param Group $group
     * @return array
     */
    private static function processGroupComponentsForInvoice(Group $group): array
    {
        $data = [];
        $totalCost = 0;
        $name = $group->name . ': ';
        foreach ($group->orderCustomers as $orderCustomer) {
            $name .= $orderCustomer->customer_name . ', ';
        }
        foreach ($group->rooms as $orderInventory) {
            $tourInventory = $orderInventory->tourComponent;
            if ($tourInventory->tour_component_type != 'Included') {
                $data[] = ['description' => "" . $tourInventory, 'cost' => $orderInventory->cost];
                $totalCost += $orderInventory->cost;
            }
        }
        return ['total_cost' => $totalCost, 'name' => substr($name, 0, -2), 'billables' => $data,];
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
        foreach ($order->groups() as $group) {
            $data = self::getGroupAdditionals($group);
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
        // Accommodation Additionals are going to be calculated per group (A:Celeste Gateley)
        foreach ($customer->orderActivities as $orderActivity) {
            if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderActivity, 'description' => "{$orderActivity->activityInventoryTour}  ({$customer->customer_name})"];
                $additionalValue += $orderActivity->cost;
            }
            if ($orderActivity->activityInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderActivity, 'description' => "{$orderActivity->activityInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderActivity->cost;
            }
        }
        foreach ($customer->orderFlights as $orderFlight) {
            if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderFlight, 'description' => "{$orderFlight->flightInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderFlight->cost;
            }
            if ($orderFlight->flightInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderFlight, 'description' => "{$orderFlight->flightInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderFlight->cost;
            }
        }
        foreach ($customer->orderTransports as $orderTransport) {
            if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderTransport, 'description' => "{$orderTransport->transportInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderTransport->cost;
            }
            if ($orderTransport->transportInventoryTour->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderTransport, 'description' => "{$orderTransport->transportInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderTransport->cost;
            }
        }
        foreach ($customer->orderMerchandise as $orderMerchandise) {
            if ($orderMerchandise->merchandise->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderMerchandise, 'description' => "{$orderMerchandise->merchandise}  ({$customer->customer_name})",];
                $additionalValue += $orderMerchandise->cost;
            }
        }
        return ['addons' => $addons, 'upgrades' => $upgrades, 'additionalValue' => $additionalValue,];
    }

    public static function getGroupAdditionals(Group $group): array
    {
        $upgrades = [];
        $addons = [];
        $additionalValue = 0;
        foreach ($group->rooms as $orderAccommodation) {
            if ($orderAccommodation->tourComponent->tour_component_type == OrderRepository::$upgradeId) {
                $upgrades[] = ['upgrade' => $orderAccommodation, 'description' => "{$orderAccommodation->tourComponent}  ({$group->name})"];
                $additionalValue += $orderAccommodation->cost;
            }
            if ($orderAccommodation->tourComponent->tour_component_type == OrderRepository::$addonId) {
                $addons[] = ['addon' => $orderAccommodation, 'description' => "{$orderAccommodation->tourComponent}  ({$group->name})",];
                $additionalValue += $orderAccommodation->cost;
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
            } else if ($paidAmount <= $order->calculated_deposit) {
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
            if ($orderCustomer->has_surcharge) $customerValue += $orderCustomer->single_occupancy_surcharge;
            $customerData = [];
            $data = self::getCustomerAdditionals($orderCustomer);
            $customerData['upgrades'] = $data['upgrades'];
            $customerData['addons'] = $data['addons'];
            $customerValue += $data['additionalValue'];
            $customerData['additionalValue'] = $customerValue;
            $total += $customerValue;
            $breakdown['customers'][] = $customerData;
        }
        foreach ($order->groups() as $group) {
            $groupData = [];
            $data = self::getGroupAdditionals($group);
            $groupData['upgrades'] = $data['upgrades'];
            $groupData['addons'] = $data['addons'];
            $groupData['additionalValue'] = $data['additionalValue'];
            $total += $data['additionalValue'];
            $breakdown['groups'][] = $groupData;
        }
        $breakdown['deposit'] = $order->calculated_deposit;
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
        $paid -= $order->calculated_deposit; // Deposit must be removed as it is an installment, but not treated as one (Celeste)
        foreach ($order->installments as $installment) {
            $paid -= $installment->calculated_amount;
            if ($paid < 0) {
                return [
                    'amount' => $installment->calculated_amount < $paid * -1 ? $installment->calculated_amount : $paid * -1,
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
     * Adding the included should not be invoiced, as it would create many invoices with little to no changes on them
     * This method should only be called when an order is intially created
     * @param OrderCustomer $orderCustomer
     */
    public static function addIncludedToCustomer(OrderCustomer $orderCustomer)
    {
        $order = $orderCustomer->order;
        // Accommodation are added to groups not customers (A:Celeste Gateley)
        foreach ($order->tour->activityInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderActivity::make(['activity_inventory_tour_id' => $inventoryTour->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderActivities()->save($orderInventory);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
            }
        }
        foreach ($order->tour->flightInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderFlight::make(['flight_inventory_tour_id' => $inventoryTour->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderFlights()->save($orderInventory);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
            }
        }
        foreach ($order->tour->transportInventoryTours as $inventoryTour) {
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderTransport::make(['transport_inventory_tour_id' => $inventoryTour->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderTransports()->save($orderInventory);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
            }
        }
        foreach ($order->tour->merchandise as $merchandise) {
            if ($merchandise->tour_component_type === "Included") {
                $orderMerchandise = OrderMerchandise::make(['merchandise_id' => $merchandise->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderMerchandise()->save($orderMerchandise);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
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
        $merchandise = Merchandise::findOrFail($merchandiseId);
        foreach ($oCustomer->orderMerchandise as $oMerch) {
            if ($oMerch->merchandise->id == $merchandiseId) return null;
        }
        $oMerch = OrderMerchandise::make(['merchandise_id' => $merchandiseId, 'cost' => $merchandise->tour_sales_price,]);
        $oCustomer->orderMerchandise()->save($oMerch);
        event(new OrderCustomerComponentAddedEvent($oMerch));
        return $oMerch;
    }

    /**
     * Iterates through all orders, and if they have a due installment, sends an email reminder
     */
    public static function sendAllOrderReminders(int $days)
    {
        foreach (Order::all() as $order) {
            $nextPayment = self::getNextPaymentDetails($order);
            if (!isset($nextPayment['installment']) || Carbon::parse($nextPayment['due'])->diffInDays(now(), true) > $days) continue;
            $reminder = PaymentReminder::where('order_id', '=', $order->id)->andWhere('payment_installment_id', '=', $nextPayment['installment']->id)->andWhere('period', '=', $days)->first();
            if (isset($reminder)) continue;
            self::sendReminderEmail($order, $nextPayment['installment']->id, $days);
        }
    }

    /**
     * Sends an email reminder of a due installment
     * @param Order $order
     * @param OrderInstallment $installment
     * @param int $days
     */
    public static function sendReminderEmail(Order $order, OrderInstallment $installment, int $days)
    {
        PaymentReminder::create([
            'order_id' => $order->id,
            'payment_installment_id' => $installment->id,
            'period' => $days
        ]);
        if ($days < 0) {
            MailRepository::sendMailable('payment-overdue', $order->leadBooker->email, $order);
        } else {
            MailRepository::sendMailable('payment-due', $order->leadBooker->email, $order);
        }
    }

    /**
     * Clone tour installments into order installments
     * @param Order $order
     */
    public static function cloneInstallments(Order $order)
    {
        foreach ($order->tour->paymentInstallments as $installment) {
            $oInstallment = OrderInstallment::make([
                'amount' => $installment->cost,
                'due_on' => $installment->due_on,
            ]);
            $order->installments()->save($oInstallment);
        }
    }

    // TODO: REWORK
    public static function getOrderFromBookingReference(string $bookingReference): ?Order
    {
        return Order::where('booking_reference', '=', $bookingReference)->first();
    }

    public static function isLeadBooker(Order $order, Customer $customer): bool
    {
        return $order->leadBooker->customer_id == $customer->id;
    }

    public static function isOrderCustomer(Order $order, Customer $customer): bool
    {
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) return true;
        }
        return false;
    }

    public static function getCustomersForOrder(Order $order)
    {
        $customers = [];
        foreach ($order->orderCustomers as $orderCustomer) {
            $customers[] = $orderCustomer->customer;
        }
        return collect($customers);
    }

    public static function isInstallmentPaid(OrderInstallment $installment): bool
    {
        $order = $installment->order;
        $paid = $order->getAdjustmentValue() + $order->getPaid() - $order->calculated_deposit;
        foreach ($order->installments as $orderInstallment) {
            $paid -= $orderInstallment->calculated_amount;
            if ($paid < 0) return false;
            if ($orderInstallment->id == $installment->id) return true;
        }
        return $paid >= 0;
    }

    public static function getOrderGroups(Order $order)
    {
        $customers = $order->orderCustomers;
        $groupIds = [];
        foreach ($customers as $customer) {
            foreach ($customer->groups as $group) {
                $groupIds[] = $group->id;
            }
        }
        $groups = [];
        foreach (array_unique($groupIds) as $groupId) {
            $groups[] = Group::find($groupId);
        }
        return $groups;
    }

    /**
     *
     * @param Order $order
     * @throws RoomingFailedException
     * @throws Throwable
     * @var Group $group
     */
    public static function buildGroupRooming(Order $order, $data)
    {
        try {
            DB::beginTransaction();
            $inflated = self::inflateRoomingData($data);
            foreach ($order->groups() as $group) {
                $group->delete();
            }
            foreach ($inflated as $groupData) {
                $group = Group::create([
                    'room_type_id' => $groupData->room_type->id,
                    'name' => $groupData->name,
                ]);
                foreach ($groupData->customers as $customer) {
                    $group->orderCustomers()->save($customer);
                }
                self::addRoomsToGroup($order, $group);
            }
            DB::commit();
        } catch (Throwable $e) {
            Log::error($e);
            DB::rollBack();
            throw new RoomingFailedException($e);
        }
    }

    /**
     * @throws RoomingFailedException
     */
    private static function inflateRoomingData($data): array
    {
        $inflated = [];
        foreach ($data as $object) {
            Log::error($data);
            $collection = new Collection();
            $roomType = RoomType::find($object['roomType']);
            if (!isset($roomType)) throw new RoomingFailedException('An invalid room type was provided');
            $collection->room_type = $roomType;
            $members = [];
            $collection->name = $object['name'];
            foreach ($object['customers'] as $customerId) {
                $customer = OrderCustomer::find($customerId);
                if (!isset($customer)) throw new RoomingFailedException('An invalid customer was provided');
                $members[] = $customer;
            }
            $collection->customers = $members;
            $inflated[] = $collection;
        }
        return $inflated;
    }

    /**
     * @throws RoomingFailedException
     */
    public static function addRoomsToGroup(Order $order, Group $group)
    {
        $templates = AccommodationComponentRepository::getTemplateTourInventory($order->tour);
        $roomType = $group->roomType;
        foreach ($templates as $template) {
            $found = AccommodationComponentRepository::getInventoryWithRoomType($template, $roomType);
            if (!isset($found)) throw new RoomingFailedException("Template {$template->id} has no inventory of type {$roomType->name}");
            OrderAccommodation::create([
                'accommodation_inventory_tour_id' => $found->id,
                'group_id' => $group->id,
                'cost' => $found->tour_sales_price,
            ]);
        }
    }

    public static function exportRoomingData(Order $order): array
    {
        $groups = [];
        $usedIds = [];
        foreach ($order->groups() as $group) {
            $grouping = ['name' => $group->name, 'roomType' => ['id' => $group->room_type_id, 'name' => $group->roomType->name, 'size' => $group->roomType->maximum_occupancy],];
            $customers = [];
            foreach ($group->orderCustomers as $orderCustomer) {
                $customers[] = ['id' => $orderCustomer->id, 'name' => $orderCustomer->customer_name, 'avatar' => asset($orderCustomer->customer->profile_picture),];
                $usedIds[] = $orderCustomer->id;
            }
            $grouping['customers'] = $customers;
            $groups[] = $grouping;
        }
        $data['rooms'] = [];
        foreach (AccommodationComponentRepository::getAvailableRoomTypes($order->tour) as $roomType) {
            $data['rooms'][] = ['id' => $roomType->id, 'name' => $roomType->name, 'size' => $roomType->maximum_occupancy,];
        }
        $data['groups'] = $groups;
        $data['customers'] = [];
        $data['unused'] = [];
        $unused = array_diff(self::getOrderCustomerIds($order), $usedIds);
        foreach ($order->orderCustomers as $orderCustomer) {
            $customerData = ['id' => $orderCustomer->id, 'name' => $orderCustomer->customer_name, 'avatar' => asset($orderCustomer->customer->profile_picture),];
            $data['customers'][] = $customerData;
            if (in_array($orderCustomer->id, $unused)) {
                $data['unused'][] = $customerData;
            }
        }
        return $data;
    }

    private static function getOrderCustomerIds(Order $order): array
    {
        $query = DB::table('order_customers')->where('order_id', '=', $order->id)->select('id');
        $ids = [];
        foreach ($query->get() as $result) {
            $ids[] = $result->id;
        }
        return $ids;
    }

    private static function generateAtolCertificate(Order $order): Pdf
    {
        $data = self::generateFlightList($order);
        $protected = $data['normal'];
        $excess = $data['excess'];
        $pdf = new Pdf(\Storage::path('templates/' . (empty($excess) ? 'atol-template.pdf' : 'atol-template-excess.pdf')));
        $pdf->fillForm([
            'companyName' => SettingsRepository::get('company.name'),
            'issuerName' => SettingsRepository::get('atol.issuer'),
            'issueDate' => \StringFormatter::formatDate($order->ordered_on),
            'atolNumber' => SettingsRepository::get('atol.number'),
            'reference' => $order->booking_reference,
            'customerNames' => $order->customer_names,
            'customerCount' => $order->getCustomerCount(),
            'protected' => $protected,
            'excess' => $excess,
        ])->flatten();

        return $pdf;
    }
    public static function assignDefaultRooming(OrderCustomer $orderCustomer)
    {
        $singleRoom = null;
        foreach (AccommodationComponentRepository::getAvailableRoomTypes($orderCustomer->order->tour) as $roomType) {
            if ($roomType->maximum_occupancy != 1) continue;
            $singleRoom = $roomType;
            break;
        }
        if (!isset($singleRoom)) return false;
        $group = Group::create([
            'room_type_id' => $singleRoom->id,
            'name' => $orderCustomer->customer_name,
        ]);
        (new GroupRepository($group))->addCustomerToGroup($orderCustomer);
        try {
            self::addRoomsToGroup($orderCustomer->order, $group);
            return true;
        } catch (RoomingFailedException $e) {
            Log::error($e->getMessage());
            return false;
        }
    }


    public static function showAtolCertificate(Order $order)
    {
        return self::generateAtolCertificate($order)->send();
    }

    public static function generateAllAtolCertificates(Tour $tour): ?string
    {
        Storage::makeDirectory('uploads/atol');
        while (true) {
            try {
                $filename = str_replace(' ', '_', strtolower($tour->name)) . '-' . now()->unix();
                $directory = 'public/' . $filename;
                if (Storage::exists($directory)) continue;
                Storage::makeDirectory($directory);
                break;
            } catch (\Exception $e) {
                continue;
            }
        }
        foreach ($tour->orders as $order) {
            if ($order->cancelled) continue;
            $atol = self::generateAtolCertificate($order);
            $atol->saveAs(Storage::path($directory) . '/' . $order->booking_reference . '.pdf');
        }
        $zip = new ZipArchive();
        if ($zip->open(Storage::path('uploads/atol/' . $filename . '.zip'), ZipArchive::CREATE) === true) {
            foreach (Storage::files($directory) as $file) {
                $exploded = explode('/', $file);
                $zip->addFile(Storage::path($file), trim(end($exploded)));
            }
            $zip->close();
            Storage::deleteDirectory($directory);
            return asset('uploads/atol/' . $filename . '.zip');
        }
        return null;
    }

    private static function generateFlightList(Order $order): array
    {
        $inbound = [];
        $outbound = [];
        foreach ($order->orderCustomers as $orderCustomer) {
            foreach ($orderCustomer->orderFlights as $orderFlight) {
                if ($orderFlight->tourComponent->flight_type == 'Inbound') {
                    $inbound[$orderFlight->tourComponent->id] = $orderFlight->tourComponent;
                } elseif ($orderFlight->tourComponent->flight_type == 'Outbound') {
                    $outbound[$orderFlight->tourComponent->id] = $orderFlight->tourComponent;
                }
            }
        }
        $string = '';
        $excessString = '';
        $excess = 3 + (count($outbound) < 3 ? 3 - count($outbound) : 0);
        foreach ($inbound as $tourComponent) {
            if ($excess > 0) {
                $string .= $tourComponent->atol_string . "\n";
                $excess--;
            }
            else {
                $excessString .= $tourComponent->atol_string . "\n";
            }
        }
        $excess += 3;
        foreach ($outbound as $tourComponent) {
            if ($excess > 0) {
                $string .= $tourComponent->atol_string . "\n";
                $excess--;
            }
            else {
                $excessString .= $tourComponent->atol_string . "\n";
            }
        }
        return ['normal' => $string, 'excess' => $excessString,];
    }
}
