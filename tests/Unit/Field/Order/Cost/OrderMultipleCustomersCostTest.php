<?php

namespace Field\Order\Cost;

use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Activity\ActivityInventoryTour;
use App\Models\Flight\FlightInventoryTour;
use App\Models\Transport\TransportInventoryTour;
use App\Repository\GroupRepository;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

/**
 * @covers Order::getCostAttribute
 * @covers \App\Repository\OrderRepository::getCost
 */
class OrderMultipleCustomersCostTest extends DatabaseTestCase
{
    use TestsOrder;

    public function testOrderCostDoubleWithSurcharge()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        self::assertEquals($this->getDefaultCost($orderCustomer->order), $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleWithoutSurcharge()
    {
        $orderCustomer1 = $this->generateOrderCustomer();
        $orderCustomer2 = $this->generateOrderCustomer(false, $orderCustomer1->order);
        $orderCustomer2->groups()->delete();
        $repo = new GroupRepository($orderCustomer1->primary_group);
        $repo->addCustomerToGroup($orderCustomer2);
        self::assertEquals($this->getDefaultCost($orderCustomer1->order), $orderCustomer1->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerAccommodationUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $tourComponent = AccommodationInventoryTour::create(
            ['accommodation_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',                ]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer->primary_group);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerAccommodationUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->primary_group->rooms[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Add-on']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleAccommodationUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = AccommodationInventoryTour::create(
                ['accommodation_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',                ]);
            $upgradeRoom = $tourComponent->addToOrder($orderCustomer->primary_group);
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $upgradeRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleAccommodationUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->primary_group->rooms as $room) {
                $tourRoom = $room->tourComponent;
                $room->update(['cost' => 100,]);
                $room->save();
                $tourRoom->update(['tour_component_type' => 'Upgrade']);
                $tourRoom->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerActivityUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $tourComponent = ActivityInventoryTour::create(
            ['activity_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerActivityUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->orderActivities[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Upgrade']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleActivityUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = ActivityInventoryTour::create(
                ['activity_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',]);
            $orderComponent = $tourComponent->addToOrder($orderCustomer);
            $orderComponent->update(['cost' => 100,]);
            $orderComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleActivityUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->orderActivities as $orderComponent) {
                $tourComponent = $orderComponent->tourComponent;
                $orderComponent->update(['cost' => 100,]);
                $orderComponent->save();
                $tourComponent->update(['tour_component_type' => 'Upgrade']);
                $tourComponent->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerFlightUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $tourComponent = FlightInventoryTour::create(
            ['flight_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerFlightUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->orderFlights[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Upgrade']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleFlightUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = FlightInventoryTour::create(
                ['flight_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',]);
            $orderComponent = $tourComponent->addToOrder($orderCustomer);
            $orderComponent->update(['cost' => 100,]);
            $orderComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleFlightUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->orderFlights as $orderComponent) {
                $tourComponent = $orderComponent->tourComponent;
                $orderComponent->update(['cost' => 100,]);
                $orderComponent->save();
                $tourComponent->update(['tour_component_type' => 'Upgrade']);
                $tourComponent->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerTransportUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $tourComponent = TransportInventoryTour::create(
            ['transport_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerTransportUpgrade()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->orderTransports[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Upgrade']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleTransportUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = TransportInventoryTour::create(
                ['transport_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Upgrade',]);
            $orderComponent = $tourComponent->addToOrder($orderCustomer);
            $orderComponent->update(['cost' => 100,]);
            $orderComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleTransportUpgrades()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->orderTransports as $orderComponent) {
                $tourComponent = $orderComponent->tourComponent;
                $orderComponent->update(['cost' => 100,]);
                $orderComponent->save();
                $tourComponent->update(['tour_component_type' => 'Upgrade']);
                $tourComponent->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerAccommodationAddon()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $tourComponent = AccommodationInventoryTour::create(
            ['accommodation_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',                ]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer->primary_group);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerAccommodationAddon()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->primary_group->rooms[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Add-on']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleAccommodationAddons()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = AccommodationInventoryTour::create(
                ['accommodation_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',                ]);
            $upgradeRoom = $tourComponent->addToOrder($orderCustomer->primary_group);
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $upgradeRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleAccommodationAddons()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $this->generateOrderCustomer(false, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->primary_group->rooms as $room) {
                $tourRoom = $room->tourComponent;
                $room->update(['cost' => 100,]);
                $room->save();
                $tourRoom->update(['tour_component_type' => 'Add-on']);
                $tourRoom->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerActivityAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $tourComponent = ActivityInventoryTour::create(
            ['activity_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerActivityAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->orderActivities[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Add-on']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleActivityAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = ActivityInventoryTour::create(
                ['activity_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',]);
            $orderComponent = $tourComponent->addToOrder($orderCustomer);
            $orderComponent->update(['cost' => 100,]);
            $orderComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleActivityAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->orderActivities as $orderComponent) {
                $tourComponent = $orderComponent->tourComponent;
                $orderComponent->update(['cost' => 100,]);
                $orderComponent->save();
                $tourComponent->update(['tour_component_type' => 'Add-on']);
                $tourComponent->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerFlightAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $tourComponent = FlightInventoryTour::create(
            ['flight_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerFlightAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->orderFlights[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Add-on']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleFlightAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = FlightInventoryTour::create(
                ['flight_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',]);
            $orderComponent = $tourComponent->addToOrder($orderCustomer);
            $orderComponent->update(['cost' => 100,]);
            $orderComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleFlightAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->orderFlights as $orderComponent) {
                $tourComponent = $orderComponent->tourComponent;
                $orderComponent->update(['cost' => 100,]);
                $orderComponent->save();
                $tourComponent->update(['tour_component_type' => 'Add-on']);
                $tourComponent->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerTransportAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $tourComponent = TransportInventoryTour::create(
            ['transport_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',]);
        $upgradeRoom = $tourComponent->addToOrder($orderCustomer);
        $upgradeRoom->update(['cost' => 100,]);
        $upgradeRoom->save();
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerTransportAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $upgradeRoom = $oCustomer->orderTransports[0];
            $tourRoom = $upgradeRoom->tourComponent;
            $upgradeRoom->update(['cost' => 100,]);
            $upgradeRoom->save();
            $tourRoom->update(['tour_component_type' => 'Add-on']);
            $tourRoom->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleTransportAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $tourComponent = TransportInventoryTour::create(
                ['transport_inventory_id' => 1, 'tour_id' => $orderCustomer->order->id, 'tour_sales_price' => 100, 'tour_component_type' => 'Add-on',]);
            $orderComponent = $tourComponent->addToOrder($orderCustomer);
            $orderComponent->update(['cost' => 100,]);
            $orderComponent->save();
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleTransportAddons()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            foreach ($oCustomer->orderTransports as $orderComponent) {
                $tourComponent = $orderComponent->tourComponent;
                $orderComponent->update(['cost' => 100,]);
                $orderComponent->save();
                $tourComponent->update(['tour_component_type' => 'Add-on']);
                $tourComponent->save();
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleCustomerMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $this->generateMerchandise($orderCustomer->order->tour, 100, 'Add-on')->addToOrder($orderCustomer);
        self::assertEquals($this->getDefaultCost($orderCustomer->order) + 100, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllCustomerMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            $this->generateMerchandise($oCustomer->order->tour, 100, 'Add-on')->addToOrder($oCustomer);
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleSingleWithMultipleMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        for ($i = 0; $i < 5; $i++) {
            $this->generateMerchandise($orderCustomer->order->tour, 100, 'Add-on')->addToOrder($orderCustomer);
            $cost += 100;
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }

    public function testOrderCostDoubleAllWithMultipleMerchandiseAddon()
    {
        $orderCustomer = $this->generateOrderCustomer(true);
        $this->generateOrderCustomer(true, $orderCustomer->order);
        $cost = $this->getDefaultCost($orderCustomer->order);
        foreach ($orderCustomer->order->orderCustomers as $oCustomer) {
            for ($i = 0; $i < 5; $i++) {
                $this->generateMerchandise($oCustomer->order->tour, 100, 'Add-on')->addToOrder($oCustomer);
                $cost += 100;
            }
        }
        self::assertEquals($cost, $orderCustomer->order->cost);
    }
}
