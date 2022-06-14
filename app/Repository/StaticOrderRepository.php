<?php

namespace App\Repository;

use App\Models\Customer\Group;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;

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
        foreach ($order->groups as $group) {
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
}
