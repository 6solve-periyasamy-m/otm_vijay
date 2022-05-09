<?php

namespace Field\Order\Cost;

use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

class OrderSingleCustomerCostTest extends DatabaseTestCase
{
    use TestsOrder;
    
    public function testOrderCostSingleWithSurcharge()
    {
        $orderCustomer = $this->generateOrderCustomer();
        self::assertEquals($orderCustomer->tour_cost + $orderCustomer->single_occupancy_surcharge, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleAccommodationUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $upgradeRoom = $orderCustomer->primary_group->rooms[0];
        $tourRoom = $upgradeRoom->tourComponent;
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        $tourRoom->update(['tour_component_type' => 'Upgrade']);
        $tourRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleAccommodationUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->primary_group->rooms as $room) {
            $tourRoom = $room->tourComponent;
            $room->update(['cost' => 100,]);
            $room->save();
            $tourRoom->update(['tour_component_type' => 'Upgrade']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleActivityUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $component = $orderCustomer->orderActivities[0];
        $tourComponent = $component->tourComponent;
        $component->update(['cost' => 100,]);
        $component->save();
        $tourComponent->update(['tour_component_type' => 'Upgrade']);
        $tourComponent->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleActivityUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->orderActivities as $component) {
            $tourComponent = $component->tourComponent;
            $component->update(['cost' => 100,]);
            $component->save();
            $tourComponent->update(['tour_component_type' => 'Upgrade']);
            $tourComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleFlightUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $component = $orderCustomer->orderFlights[0];
        $tourComponent = $component->tourComponent;
        $component->update(['cost' => 100,]);
        $component->save();
        $tourComponent->update(['tour_component_type' => 'Upgrade']);
        $tourComponent->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleFlightUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->orderFlights as $component) {
            $tourComponent = $component->tourComponent;
            $component->update(['cost' => 100,]);
            $component->save();
            $tourComponent->update(['tour_component_type' => 'Upgrade']);
            $tourComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleTransportUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $component = $orderCustomer->orderTransports[0];
        $tourComponent = $component->tourComponent;
        $component->update(['cost' => 100,]);
        $component->save();
        $tourComponent->update(['tour_component_type' => 'Upgrade']);
        $tourComponent->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleTransportUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $orderCustomer->tour_cost + $orderCustomer->single_occupancy_surcharge;
        foreach ($orderCustomer->orderTransports as $component) {
            $tourComponent = $component->tourComponent;
            $component->update(['cost' => 100,]);
            $component->save();
            $tourComponent->update(['tour_component_type' => 'Upgrade']);
            $tourComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }
    
    public function testOrderCostSingleWithSurchargeAndSingleAccommodationAddon()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $addonRoom = $orderCustomer->primary_group->rooms[0];
        $tourRoom = $addonRoom->tourComponent;
        $addonRoom->update(['cost' => 100,]);
        $addonRoom->save();
        $tourRoom->update(['tour_component_type' => 'Add-on']);
        $tourRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleAccommodationAddon()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $cost = $orderCustomer->tour_cost + $orderCustomer->single_occupancy_surcharge;
        foreach ($orderCustomer->primary_group->rooms as $room) {
            $tourRoom = $room->tourComponent;
            $room->update(['cost' => 100,]);
            $room->save();
            $tourRoom->update(['tour_component_type' => 'Add-on']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleActivityAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $component = $orderCustomer->orderActivities[0];
        $tourComponent = $component->tourComponent;
        $component->update(['cost' => 100,]);
        $component->save();
        $tourComponent->update(['tour_component_type' => 'Add-on']);
        $tourComponent->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleActivityAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->orderActivities as $component) {
            $tourComponent = $component->tourComponent;
            $component->update(['cost' => 100,]);
            $component->save();
            $tourComponent->update(['tour_component_type' => 'Add-on']);
            $tourComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleFlightAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $component = $orderCustomer->orderFlights[0];
        $tourComponent = $component->tourComponent;
        $component->update(['cost' => 100,]);
        $component->save();
        $tourComponent->update(['tour_component_type' => 'Add-on']);
        $tourComponent->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleFlightAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->orderFlights as $component) {
            $tourComponent = $component->tourComponent;
            $component->update(['cost' => 100,]);
            $component->save();
            $tourComponent->update(['tour_component_type' => 'Add-on']);
            $tourComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleTransportAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $component = $orderCustomer->orderTransports[0];
        $tourComponent = $component->tourComponent;
        $component->update(['cost' => 100,]);
        $component->save();
        $tourComponent->update(['tour_component_type' => 'Add-on']);
        $tourComponent->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleTransportAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->orderTransports as $component) {
            $tourComponent = $component->tourComponent;
            $component->update(['cost' => 100,]);
            $component->save();
            $tourComponent->update(['tour_component_type' => 'Add-on']);
            $tourComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $tourComponent = $this->generateMerchandise($orderCustomer->order->tour, 100, 'Add-on');
        $tourComponent->addToOrder($orderCustomer);
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = $this->generateMerchandise($orderCustomer->order->tour, 100, 'Add-on');
            $tourComponent->addToOrder($orderCustomer);
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }
}
