<?php

namespace App\Repository;

use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Models\Customer\Group;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\OrderInstallment;
use App\Models\Order\Payment\PaymentReminder;
use App\Models\Tour\Merchandise;
use App\Repository\Mailing\MailRepository;
use App\Repository\Model\Order\Component\OrderAccommodationRepository;
use App\Repository\Model\Order\Component\OrderActivityRepository;
use App\Repository\Model\Order\Component\OrderFlightRepository;
use App\Repository\Model\Order\Component\OrderTransportRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class StaticOrderRepository
{

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

    // Order Management Methods

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
    public static function sendAllOrderReminders(int $days, int $minDays = -1000): void
    {
        foreach (Order::where('cancelled', false)->get() as $order) {
            if ($order->cancelled) continue;

            $nextPayment = $order->next_installment;

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
    public static function sendReminderEmail(Order $order, OrderInstallment $installment, int $days): void
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
        foreach ($orderCustomer->repository->getComponents() as $orderComponentRepository) {
            $ids = [$orderComponentRepository->get()->id,];
            if ($orderComponentRepository->getTourComponentType() === 'Upgrade') {
                $ids[] = $orderComponentRepository->get()->tourComponent->parent()->id;
            }
            switch (true) {
                case $orderComponentRepository instanceof OrderAccommodationRepository:
                    $data['accommodation'] = array_merge($data['accommodation'], $ids);
                    break;
                case $orderComponentRepository instanceof OrderActivityRepository:
                    $data['accommodation'] = array_merge($data['activities'], $ids);
                    break;
                case $orderComponentRepository instanceof OrderFlightRepository:
                    $data['accommodation'] = array_merge($data['flights'], $ids);
                    break;
                case $orderComponentRepository instanceof OrderTransportRepository:
                    $data['accommodation'] = array_merge($data['transports'], $ids);
                    break;
            }
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
