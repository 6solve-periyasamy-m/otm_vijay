<?php

namespace Api;

use Tests\AuthenticationTestCase;
use Tests\Traits\TestsOrder;

class AdminAddAccommodationAddonApiTest extends AuthenticationTestCase
{
    use TestsOrder;

    public function testWithCorrectData()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $inventoryTour = $this->generateAccommodationInventoryTour($orderCustomer->order->tour, 'Add-on');
        $response = $this->postJson(route('api.order.addon.add.accommodation'),
            ['customer_id' => $orderCustomer->id,
             'accommodation_id' => $inventoryTour->id,
             '__api_token' => $this->user()->getCurrentToken()->token,]);
        $response->assertStatus(201);
    }

    public function testWithoutAuthentication()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $inventoryTour = $this->generateAccommodationInventoryTour($orderCustomer->order->tour, 'Add-on');
        $response = $this->postJson(route('api.order.addon.add.accommodation'),
            ['customer_id' => $orderCustomer->id,
                'accommodation_id' => $inventoryTour->id,]);
        $response->assertStatus(403);
    }

    public function testWithUnknownOrderCustomer()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $inventoryTour = $this->generateAccommodationInventoryTour($orderCustomer->order->tour, 'Add-on');
        $response = $this->postJson(route('api.order.addon.add.accommodation'),
            ['customer_id' => 0,
                'accommodation_id' => $inventoryTour->id,
                '__api_token' => $this->user()->getCurrentToken()->token,]);
        $response->assertStatus(422);
    }

    public function testWithUnknownInventoryTour()
    {
        $orderCustomer = $this->generateOrderCustomer();
        $response = $this->postJson(route('api.order.addon.add.accommodation'),
            ['customer_id' => $orderCustomer->id,
                'accommodation_id' => 0,
                '__api_token' => $this->user()->getCurrentToken()->token,]);
        $response->assertStatus(422);
    }
}