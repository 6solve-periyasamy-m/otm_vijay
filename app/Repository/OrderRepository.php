<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Exceptions\RoomingFailedException;
use App\Models\Accommodation\RoomType;
use App\Models\Customer\Customer;
use App\Models\Customer\Group;
use App\Models\Helper\OrderStatus;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Invoice;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\PaymentReminder;
use App\Models\Tour\Merchandise;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Log;
use mikehaertl\pdftk\Pdf;
use Storage;
use StringFormatter;
use Throwable;
use ZipArchive;

class OrderRepository
{
    public static function saveInvoice(Order $order): Invoice
    {
        $invoice = self::generateInvoice($order);
        $invoice->save();
        return $invoice;
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
            'total_cost' => $order->cost + $order->getAdjustmentValue(),
        ]);
    }

    private static function processCustomerComponentsForInvoice(OrderCustomer $orderCustomer): array
    {
        $data = [];
        $totalCost = 0;
        $included = "Base Components Include:\n";
        if ($orderCustomer->has_surcharge) {
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

    public static function snapshotInstallments(Order $order): array
    {
        $data = [['due' => 'With Order',
            'description' => self::buildInstallmentString('Deposit', $order, $order->deposit, $order->calculated_deposit),
            'amount' => $order->calculated_deposit, 'paid' => $order->paid >= $order->calculated_deposit,]];
        foreach ($order->installments as $installment) {
            $data[] = ['due' => $installment->due_on, 'description' => self::buildInstallmentString('Installment', $order, $installment->amount, $installment->calculated_amount),
                'amount' => $installment->calculated_amount, 'paid' => $installment->paid,];
        }
        $data[] = ['due' => $order->tour->final_payment, 'description' => 'Remaining Balance: ' . StringFormatter::formatCurrency($order->remaining_installment),
            'amount' => $order->remaining_installment, 'paid' => $order->paid >= $order->cost,];
        return $data;
    }

    public static function buildInstallmentString(string $type, Order $order, float $amount, float $calculated): string
    {
        return $type . ': ' . $order->customer_count . ' Customer' . ($order->customer_count > 1 ? 's' : '') . ' x '
            . StringFormatter::formatCurrency($amount) . ' = ' . StringFormatter::formatCurrency($calculated);
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
            if ($orderActivity->activityInventoryTour->tour_component_type == 'Upgrade') {
                $upgrades[] = ['upgrade' => $orderActivity, 'description' => "{$orderActivity->activityInventoryTour}  ({$customer->customer_name})"];
                $additionalValue += $orderActivity->cost;
            }
            if ($orderActivity->activityInventoryTour->tour_component_type == 'Add-on') {
                $addons[] = ['addon' => $orderActivity, 'description' => "{$orderActivity->activityInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderActivity->cost;
            }
        }
        foreach ($customer->orderFlights as $orderFlight) {
            if ($orderFlight->flightInventoryTour->tour_component_type == 'Upgrade') {
                $upgrades[] = ['upgrade' => $orderFlight, 'description' => "{$orderFlight->flightInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderFlight->cost;
            }
            if ($orderFlight->flightInventoryTour->tour_component_type == 'Add-on') {
                $addons[] = ['addon' => $orderFlight, 'description' => "{$orderFlight->flightInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderFlight->cost;
            }
        }
        foreach ($customer->orderTransports as $orderTransport) {
            if ($orderTransport->transportInventoryTour->tour_component_type == 'Upgrade') {
                $upgrades[] = ['upgrade' => $orderTransport, 'description' => "{$orderTransport->transportInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderTransport->cost;
            }
            if ($orderTransport->transportInventoryTour->tour_component_type == 'Add-on') {
                $addons[] = ['addon' => $orderTransport, 'description' => "{$orderTransport->transportInventoryTour}  ({$customer->customer_name})",];
                $additionalValue += $orderTransport->cost;
            }
        }
        foreach ($customer->orderMerchandise as $orderMerchandise) {
            if ($orderMerchandise->merchandise->tour_component_type == 'Add-on') {
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
            if ($orderAccommodation->tourComponent->tour_component_type == 'Upgrade') {
                $upgrades[] = ['upgrade' => $orderAccommodation, 'description' => "{$orderAccommodation->tourComponent}  ({$group->name})"];
                $additionalValue += $orderAccommodation->cost;
            }
            if ($orderAccommodation->tourComponent->tour_component_type == 'Add-on') {
                $addons[] = ['addon' => $orderAccommodation, 'description' => "{$orderAccommodation->tourComponent}  ({$group->name})",];
                $additionalValue += $orderAccommodation->cost;
            }
        }
        return ['addons' => $addons, 'upgrades' => $upgrades, 'additionalValue' => $additionalValue,];
    }

    // Order/Customer Adjustments

    /**
     * Get the current status of the order
     * @param Order $order
     * @return OrderStatus Status code for order
     */
    public static function getOrderStatus(Order $order): OrderStatus
    {
        $paidAmount = $order->paid;
        $cost = self::getCost($order);
        $adjustments = self::getTotalAdjustedValue($order);
        $total = $cost + $adjustments;
        if ($order->trashed() || $order->cancelled) {
            if ($paidAmount == 0) {
                return OrderStatus::CANCELLED_FULL_REFUND;
            } else if ($paidAmount <= $order->calculated_deposit) {
                return OrderStatus::CANCELLED_DEPOSIT_HELD;
            } else {
                return OrderStatus::CANCELLED_REFUND_REQUIRED;
            }
        } else {
            foreach ($order->orderCustomers as $orderCustomer) {
                if (!$orderCustomer->has_occupancy) return OrderStatus::OCCUPANCY_NOT_SET;
            }
            if ($total > $paidAmount) {
                $next = self::getNextPaymentDetails($order);
                if (isset($next) && Carbon::now()->isAfter($next->due_on)) {
                    return OrderStatus::PAYMENT_OVERDUE;
                } else {
                    return OrderStatus::BALANCE_OUTSTANDING;
                }
            } elseif ($total < $paidAmount) {
                return OrderStatus::OVERPAID;
            } else {
                return OrderStatus::PAID_IN_FULL;
            }
        }
    }

    /**
     * Get total cost amount for an order
     * @param Order $order
     * @return float The total cost of the order
     */
    public static function getCost(Order $order): float
    {
        $total = 0;
        foreach ($order->orderCustomers as $orderCustomer) {
            $total += $orderCustomer->tour_cost;
            if ($orderCustomer->has_surcharge) $total += $orderCustomer->single_occupancy_surcharge;
            foreach ($orderCustomer->orderActivities as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
            foreach ($orderCustomer->orderFlights as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
            foreach ($orderCustomer->orderTransports as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
            foreach ($orderCustomer->orderMerchandise as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
        }
        foreach ($order->groups() as $group) {
            foreach ($group->rooms as $orderComponent) {
                if ($orderComponent->tourComponent->tour_component_type == 'Included') continue;
                $total += $orderComponent->cost;
            }
        }
        return $total;
    }

    // Order Costs

    /**
     * Get the sum of the customer and order adjustments
     * @param Order $order
     * @return float Sum of the two adjustment values
     */
    public static function getTotalAdjustedValue(Order $order): float
    {
        return self::getCustomerAdjustmentTotal($order) + self::getOrderAdjustmentTotal($order);
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

    // Order Payments

    /**
     * Get details about the next payment
     * @param Order $order
     * @return OrderInstallment|null Details about the next installment. If installment is null, then no more installments are required
     */
    public static function getNextPaymentDetails(Order $order): ?OrderInstallment
    {
        $paid = $order->paid;
        $paid -= self::getTotalAdjustedValue($order); // Negative adjustments add to the total paid, so minus is required
        $paid -= $order->calculated_deposit; // Deposit must be removed as it is an installment, but not treated as one (Celeste)
        $paid = sigfig($paid);
        foreach ($order->installments as $installment) {
            $paid -= $installment->calculated_amount;
            $paid = sigfig($paid);
            if ($paid < 0) {
                return new OrderInstallment([
                    'amount' => min($installment->calculated_amount, $paid * -1),
                    'due_on' => $installment->due_on,
                    'order_id' => $order->id,
                ]);
            }
        }
        return null;
    }

    /**
     * Get the amount the order has left to pay
     * @param Order $order
     * @return float The remaining amount required on the order
     */
    public static function getRemainingToPay(Order $order): float
    {
        $cost = $order->cost;
        $paid = $order->paid;
        $adjustments = $order->getAdjustmentValue();
        return ($cost + $adjustments) - $paid;
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
            if (!$inventoryTour->is_bookable) continue;
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderActivity::make(['activity_inventory_tour_id' => $inventoryTour->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderActivities()->save($orderInventory);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
            }
        }
        foreach ($order->tour->flightInventoryTours as $inventoryTour) {
            if (!$inventoryTour->is_bookable) continue;
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderFlight::make(['flight_inventory_tour_id' => $inventoryTour->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderFlights()->save($orderInventory);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
            }
        }
        foreach ($order->tour->transportInventoryTours as $inventoryTour) {
            if (!$inventoryTour->is_bookable) continue;
            if ($inventoryTour->tour_component_type === "Included") {
                $orderInventory = OrderTransport::make(['transport_inventory_tour_id' => $inventoryTour->id, 'cost' => $inventoryTour->tour_sales_price,]);
                $orderCustomer->orderTransports()->save($orderInventory);
                event(new OrderCustomerComponentAddedEvent($orderInventory, false));
            }
        }
        foreach ($order->tour->merchandise as $merchandise) {
            if (!$inventoryTour->is_bookable) continue;
            if ($merchandise->tour_component_type === "Included") {
                $orderMerchandise = OrderMerchandise::make(['merchandise_id' => $merchandise->id, 'cost' => $merchandise->tour_sales_price,]);
                $orderCustomer->orderMerchandise()->save($orderMerchandise);
                event(new OrderCustomerComponentAddedEvent($orderMerchandise, false));
            }
        }
    }

    /**
     * Adds an Add-on Merchandise to an Order Customer
     * @param int $oCustomerId
     * @param int $merchandiseId
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
     * @todo REWORK
     */
    public static function sendAllOrderReminders(int $days, int $minDays = -1000)
    {
        foreach (Order::where('cancelled', false)->get() as $order) {
            if ($order->cancelled) continue;

            $nextPayment = self::getNextPaymentDetails($order);

            if (!isset($nextPayment) ||
                !((Carbon::parse($nextPayment->due_on)->diffInDays(now()) * -1) <= $days &&
                 (Carbon::parse($nextPayment->due_on)->diffInDays(now()) * -1) > $minDays)) continue;

            $reminder = PaymentReminder::where('order_id', '=', $order->id)->where('order_installment_id', '=', $nextPayment['installment']->id)->where('period', '=', $days)->first();

            if (isset($reminder)) continue;

            self::sendReminderEmail($order, $nextPayment['installment'], $days);
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
            'order_installment_id' => $installment->id,
            'period' => $days
        ]);
        if ($days < 0) {
            MailRepository::sendMailable('payment-overdue', $order->leadBooker->customer->email_address, $order);
        } else {
            MailRepository::sendMailable('payment-due', $order->leadBooker->customer->email_address, $order);
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

    public static function getOrderFromBookingReference(string $bookingReference): ?Order
    {
        return Order::whereBookingReference($bookingReference)->first();
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

    public static function getOrderCustomer(Order $order, Customer $customer): ?OrderCustomer
    {
        foreach ($order->orderCustomers as $orderCustomer) {
            if ($orderCustomer->customer_id == $customer->id) return $orderCustomer;
        }
        return null;
    }

    public static function getCustomersForOrder(Order $order): Collection
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
        $paid = sigfig(($order->getAdjustmentValue() * -1) + $order->paid - $order->calculated_deposit);
        foreach ($order->installments as $orderInstallment) {
            $paid = sigfig($paid - $orderInstallment->calculated_amount);
            if ($paid < 0) return false;
            if ($orderInstallment->id == $installment->id) return true;
        }
        return $paid >= 0;
    }

    public static function getOrderGroups(Order $order): array
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
            if (!array_key_exists('customers', $object)) continue;
            foreach ($object['customers'] as $customerId) {
                $customer = OrderCustomer::find($customerId);
                if (!isset($customer)) throw new RoomingFailedException('An invalid customer was provided');
                $members[] = $customer;
            }
            if (sizeof($members) < 1) continue;
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
            if (!isset($found)) {
                $types = AccommodationComponentRepository::hydrateRoomTypes(AccommodationComponentRepository::getRoomTypesForInventory($template));
                foreach ($types as $type) {
                    if ($type->maximum_occupancy == $roomType->maximum_occupancy) {
                        $found = AccommodationComponentRepository::getInventoryWithRoomType($template, $type);
                        break;
                    }
                }
            }
            if (!isset($found)) throw new RoomingFailedException("Template {$template->id} has no inventory of type {$roomType->name} or size {$roomType->maximum_occupancy}");
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

    public static function assignDefaultRooming(OrderCustomer $orderCustomer): bool
    {
        $singleRoom = null;
        foreach (AccommodationComponentRepository::getAvailableRoomTypes($orderCustomer->order->tour) as $roomType) {
            if ($singleRoom != null && $singleRoom->maximum_occupancy <= $roomType->maximum_occupancy) continue;
            $singleRoom = $roomType;
            if ($singleRoom->maximum_occupancy == 1) break;
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

    public static function showAtolCertificate(Order $order): bool
    {
        return self::generateAtolCertificate($order)->send();
    }

    private static function generateAtolCertificate(Order $order): Pdf
    {
        $data = self::generateFlightList($order);
        $protected = $data['normal'];
        $excess = $data['excess'];
        $pdf = new Pdf(Storage::path('templates/' . (empty($excess) ? 'atol-template.pdf' : 'atol-template-excess.pdf')));
        $pdf->fillForm([
            'companyName' => SettingsRepository::get('company.name'),
            'issuerName' => SettingsRepository::get('atol.issuer'),
            'issueDate' => StringFormatter::formatDate($order->ordered_on),
            'atolNumber' => SettingsRepository::get('atol.number'),
            'reference' => $order->booking_reference,
            'customerNames' => $order->customer_names,
            'customerCount' => $order->customer_count,
            'protected' => $protected,
            'excess' => $excess,
        ])->flatten();

        return $pdf;
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
            } else {
                $excessString .= $tourComponent->atol_string . "\n";
            }
        }
        $excess += 3;
        foreach ($outbound as $tourComponent) {
            if ($excess > 0) {
                $string .= $tourComponent->atol_string . "\n";
                $excess--;
            } else {
                $excessString .= $tourComponent->atol_string . "\n";
            }
        }
        return ['normal' => $string, 'excess' => $excessString,];
    }

    public static function generateAllAtolCertificates(Collection $orders, string $name): ?string
    {
        Storage::makeDirectory('uploads/atol');
        while (true) {
            try {
                $filename = str_replace(' ', '_', strtolower($name)) . '-' . now()->unix();
                $directory = 'public/' . $filename;
                if (Storage::exists($directory)) continue;
                Storage::makeDirectory($directory);
                break;
            } catch (Exception) {
                continue;
            }
        }
        foreach ($orders as $order) {
            if ($order->cancelled) continue;
            if (!$order->has_atol) continue;
            $atol = self::generateAtolCertificate($order);
            $saved = $atol->saveAs(Storage::path($directory) . '/' . $order->booking_reference . '.pdf');
            if (!$saved) {
                dd($atol->getError());
            }
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
        } else
            return null;
    }

    public static function hasFlight(Order $order): bool
    {
        $query = DB::table('order_flights');
        $query->join('order_customers', 'order_flights.order_customer_id', '=', 'order_customers.id');
        $query->join('orders', 'order_customers.order_id', '=', 'orders.id');
        $query->where('orders.id', '=', $order->id);
        $query->whereNull('order_flights.deleted_at');
        $query->select('order_flights.id');
        $results = $query->get();
        return $results->count() > 0;
    }

    public static function checkOccupancy(OrderCustomer $orderCustomer): bool
    {
        $owned = [];
        foreach ($orderCustomer->orderAccommodation() as $orderAccommodation) {
            $date = $orderAccommodation->tourComponent->inventory->check_in->clone()->setTime(0, 0);
            $owned[$date->unix()] = $orderAccommodation;
        }
        foreach ($orderCustomer->order->tour->templates as $template) {
            $date = $template->inventory->check_in->clone()->setTime(0, 0, 0);
            if (array_key_exists($date->unix(), $owned)) continue;
            return false;
        }
        return true;
    }

    #[ArrayShape(['accommodation' => "array", 'activities' => "array", 'flights' => "array", 'transports' => "array"])]
    public static function getAvailableForExtras(OrderCustomer $orderCustomer): array
    {
        $tour = $orderCustomer->order->tour;
        $data = ['accommodation' => [], 'activities' => [], 'flights' => [], 'transports' => []];
        $owned = self::getOwnedTourComponents($orderCustomer);
        $roomTypeId = $orderCustomer?->primary_group->room_type_id ?? 0;
        foreach ($tour->accommodationInventoryTours as $tourInventory) {
            $inventory = $tourInventory->inventory;
            if ($inventory->room_type_id !== $roomTypeId) continue;
            if ($tourInventory->tour_component_type == 'Upgrade') continue;
            $subData = ['id' => $tourInventory->id,
                'description' => $inventory->customer_display,
                'owned' => in_array($tourInventory->id, $owned['accommodation']),
                'upgrades' => [],
                'change' => $tourInventory->tour_component_type == 'Add-on',
                'cost' => ($tourInventory->tour_component_type == 'Add-on' ? $tourInventory->tour_sales_price : 0)];
            $upgraded = false;
            foreach ($tourInventory->upgrades as $upgrade) {
                $owns = in_array($tourInventory->id, $owned['accommodation']);
                if ($owns) $upgraded = true;
                $subData['upgrades'][] =
                    ['id' => $upgrade->upgrade_id,
                        'description' => $upgrade->description,
                        'owned' => $owns,
                        'change' => true,
                        'cost' => $upgrade->upgrade->tour_sales_price,];
            }
            $subData['upgraded'] = $upgraded;
            $data['accommodation'][] = $subData;
        }
        foreach ($tour->activityInventoryTours as $tourInventory) {
            $inventory = $tourInventory->inventory;
            if ($tourInventory->tour_component_type == 'Upgrade') continue;
            $subData = ['id' => $tourInventory->id,
                'description' => $inventory->__toString(),
                'owned' => in_array($tourInventory->id, $owned['activities']),
                'upgrades' => [], 'change' => true,
                'cost' => ($tourInventory->tour_component_type == 'Add-on' ? $tourInventory->tour_sales_price : 0)];
            $upgraded = false;
            foreach ($tourInventory->upgrades as $upgrade) {
                $owns = in_array($tourInventory->id, $owned['activities']);
                if ($owns) $upgraded = true;
                $subData['upgrades'][] =
                    ['id' => $upgrade->upgrade_id,
                        'description' => $upgrade->description,
                        'owned' => $owns,
                        'change' => true,
                        'cost' => $upgrade->upgrade->tour_sales_price,];
            }
            $subData['upgraded'] = $upgraded;
            $data['activities'][] = $subData;
        }
        foreach ($tour->flightInventoryTours as $tourInventory) {
            $inventory = $tourInventory->inventory;
            if ($tourInventory->tour_component_type == 'Upgrade') continue;
            $subData = ['id' => $tourInventory->id, 'description' => $inventory->__toString(),
                'owned' => in_array($tourInventory->id, $owned['flights']),
                'upgrades' => [],
                'change' => $tourInventory->tour_component_type == 'Add-on',
                'cost' => ($tourInventory->tour_component_type == 'Add-on' ? $tourInventory->tour_sales_price : 0)];
            $upgraded = false;
            foreach ($tourInventory->upgrades as $upgrade) {
                $owns = in_array($tourInventory->id, $owned['flights']);
                if ($owns) $upgraded = true;
                $subData['upgrades'][] =
                    ['id' => $upgrade->upgrade_id,
                        'description' => $upgrade->description,
                        'owned' => $owns,
                        'change' => true,
                        'cost' => $upgrade->upgrade->tour_sales_price,];
            }
            $subData['upgraded'] = $upgraded;
            $data['flights'][] = $subData;
        }
        foreach ($tour->transportInventoryTours as $tourInventory) {
            $inventory = $tourInventory->inventory;
            if ($tourInventory->tour_component_type == 'Upgrade') continue; // Upgrades are handled from their parent
            $subData = ['id' => $tourInventory->id, 'description' => $inventory->__toString(),
                'owned' => in_array($tourInventory->id, $owned['transports']),
                'upgrades' => [],
                'change' => $tourInventory->tour_component_type == 'Add-on',
                'cost' => ($tourInventory->tour_component_type == 'Add-on' ? $tourInventory->tour_sales_price : 0)];
            $upgraded = false;
            foreach ($tourInventory->upgrades as $upgrade) {
                $owns = in_array($tourInventory->id, $owned['transports']);
                if ($owns) $upgraded = true;
                $subData['upgrades'][] =
                    ['id' => $upgrade->upgrade_id,
                        'description' => $upgrade->description,
                        'owned' => $owns,
                        'change' => true,
                        'cost' => $upgrade->upgrade->tour_sales_price,];
            }
            $subData['upgraded'] = $upgraded;
            $data['transports'][] = $subData;
        }
        return $data;
    }

    /**
     * @param OrderCustomer $orderCustomer
     * @return array
     */
    #[ArrayShape(['accommodation' => "array", 'activities' => "array", 'flights' => "array", 'transports' => "array"])]
    private static function getOwnedTourComponents(OrderCustomer $orderCustomer): array
    {
        $data = ['accommodation' => [], 'activities' => [], 'flights' => [], 'transports' => []];
        foreach ($orderCustomer->orderAccommodation() as $orderComponent) {
            $data['accommodation'][] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade')
                $data['accommodation'][] = $orderComponent->tourComponent->parent()->id;
        }
        foreach ($orderCustomer->orderActivities as $orderComponent) {
            $data['activities'][] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade')
                $data['activities'][] = $orderComponent->tourComponent->parent()->id;
        }
        foreach ($orderCustomer->orderFlights as $orderComponent) {
            $data['flights'][] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade')
                $data['flights'][] = $orderComponent->tourComponent->parent()->id;
        }
        foreach ($orderCustomer->orderTransports as $orderComponent) {
            $data['transports'][] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade')
                $data['activities'][] = $orderComponent->tourComponent->parent()->id;
        }
        return [
            'accommodation' => array_unique($data['accommodation']),
            'activities' => array_unique($data['activities']),
            'flights' => array_unique($data['flights']),
            'transports' => array_unique($data['transports']),
        ];
    }

    public static function getAllAdditionals(Order $order): array
    {
        $data = [];
        foreach ($order->tour->merchandise as $tourComponent) {
            $data[] = ['id' => $tourComponent->id, 'name' => $tourComponent->name, 'component' => 'extra', 'type' => $tourComponent->tour_component_type,
                'cost' => $tourComponent->tour_sales_price, 'date' => now()->unix(),];
        }
        foreach ($order->tour->accommodationInventoryTours as $tourComponent) {
            if ($tourComponent->tour_component_type == 'Add-on') {
                $inventory = $tourComponent->inventory;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'accommodation', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->check_in->unix(),];
            }
        }
        foreach ($order->tour->activityInventoryTours as $tourComponent) {
            if ($tourComponent->tour_component_type == 'Add-on') {
                $inventory = $tourComponent->inventory;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'activity', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->starts_at->unix(),];
            }
        }
        foreach ($order->tour->flightInventoryTours as $tourComponent) {
            if ($tourComponent->tour_component_type == 'Add-on') {
                $inventory = $tourComponent->inventory;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'flight', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->check_in->unix(),];
            }
        }
        foreach ($order->tour->transportInventoryTours as $tourComponent) {
            if ($tourComponent->tour_component_type == 'Add-on') {
                $inventory = $tourComponent->inventory;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'transport', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->departs_at->unix(),];
            }
        }
        usort($data, function ($previous, $next) {
            return $previous['date'] <=> $next['date'];
        });
        return $data;
    }

    public static function getOrderCustomerAdditionals(OrderCustomer $orderCustomer): array
    {
        $order = $orderCustomer->order;
        $owned = self::getOwnedComponentsAndUpgradedIncluded($orderCustomer);
        $data = [];
        foreach ($order->tour->merchandise as $tourComponent) {
            $owns = in_array($tourComponent->id, $owned['extras']);
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->available_stock <= 0 && !$owned) continue;
            $data[] = ['id' => $tourComponent->id, 'name' => $tourComponent->name, 'component' => 'extra', 'type' => $tourComponent->tour_component_type,
                'cost' => $tourComponent->tour_sales_price, 'date' => now()->unix(), 'owned' => $owns,];
        }
        foreach ($order->tour->accommodationInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type == 'Add-on') {
                $inventory = $tourComponent->inventory;
                $owns = in_array($tourComponent->id, $owned['accommodation']);
                if ($tourComponent->available_stock <= 0 && !$owned) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'accommodation', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->check_in->unix(), 'owned' => $owns];
            }
        }
        foreach ($order->tour->activityInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['activities'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'activity', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->starts_at->unix(), 'owned' => false,];
            }
        }
        foreach ($order->tour->flightInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['flights'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'flight', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->check_in->unix(), 'owned' => false,];
            }
        }
        foreach ($order->tour->transportInventoryTours as $tourComponent) {
            if (!$tourComponent->is_bookable) continue;
            if ($tourComponent->tour_component_type !== 'Upgrade') {
                $inventory = $tourComponent->inventory;
                if (in_array($tourComponent->id, $owned['transport'])) continue; // Owned components will be shown elsewhere
                if ($tourComponent->available_stock <= 0) continue;
                $data[] = ['id' => $tourComponent->id, 'name' => $inventory->__toString(), 'component' => 'transport', 'type' => $tourComponent->tour_component_type,
                    'cost' => $tourComponent->tour_sales_price, 'date' => $inventory->departs_at->unix(), 'owned' => false,];
            }
        }
        return $data;
    }

    public static function getOwnedComponentsAndUpgradedIncluded(OrderCustomer $orderCustomer): array
    {
        $data = [];
        $subData = [];
        foreach ($orderCustomer->orderAccommodation() as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['accommodation'] = array_unique($subData);
        $subData = [];
        foreach ($orderCustomer->orderActivities as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['activities'] = array_unique($subData);
        $subData = [];
        foreach ($orderCustomer->orderFlights as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['flights'] = array_unique($subData);
        $subData = [];
        foreach ($orderCustomer->orderTransports as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
            if ($orderComponent->tourComponent->tour_component_type == 'Upgrade') {
                $subData[] = $orderComponent->tourComponent->parent()->id;
            }
        }
        $data['transport'] = array_unique($subData);
        $subData = [];
        foreach ($orderCustomer->orderMerchandise as $orderComponent) {
            $subData[] = $orderComponent->tourComponent->id;
        }
        $data['extras'] = array_unique($subData);
        return $data;
    }
}
