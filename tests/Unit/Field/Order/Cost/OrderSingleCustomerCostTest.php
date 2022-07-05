<?php

namespace Field\Order\Cost;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers \App\Models\Order\Order::getCostAttribute
 * @covers \App\Repository\Model\Order\OrderRepository::getCost
 */
class OrderSingleCustomerCostTest extends DatabaseTestCase
{
    use TestsOrder;
    use RefreshDatabase;
    
    public function testOrderCostSingleWithSurcharge()
    {
        $orderCustomer = $this->generateOrder()->leadBooker;
        self::assertEquals($orderCustomer->tour_cost + $orderCustomer->single_occupancy_surcharge, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndSingleAccommodationUpgrade()
    {
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
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
        $orderCustomer = $this->generateOrder()->leadBooker;
        $tourComponent = $this->generateMerchandise($orderCustomer->order->tour, 'Add-on', 100);
        $tourComponent->addToOrder($orderCustomer);
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostSingleWithSurchargeAndMultipleMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrder()->leadBooker;
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = $this->generateMerchandise($orderCustomer->order->tour, 'Add-on', 100);
            $tourComponent->addToOrder($orderCustomer);
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }
}
