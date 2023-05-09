<?php

namespace Route\Admin\Form\Order;

use App\Models\Customer\Customer;
use App\Models\Order\Order;
use Tests\Bases\Authentication\AuthenticatedFormTestCase;
use Tests\Traits\Model\TestsOrder;

class OrderFormTest extends AuthenticatedFormTestCase
{
    use TestsOrder;

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderController::store
     * @covers \App\Http\Requests\Admin\Order\CreateOrderRequest
     * @return void
     */
    public function testOrderCreation(): void
    {
        $tour = $this->generateTour();
        $customer = Customer::factory()->create();
        $route = route('orders.create');
        $id = Order::count() + 1;
        $data = ['tour_id' => $tour->id, 'lead_booker' => ['id' => $customer->id,], 'ordered_on' => now()->micro(0),]; // EQ fails without micro

        $this->performFailCases($route, (new Order())->getPermissionSet('create'), ['tour_id', 'ordered_on', 'lead_booker.id']);
        $this->performWithEverything($route, $data)->assertRedirectToRoute('orders.view', ['order' => $id]);
        $order = Order::find($id);
        $this->assertEquals($tour->id, $order->tour_id); // Check it was created for the correct tour
        $this->assertEquals($tour->deposit, $order->deposit); // No deposit was provided, so should clone from tour
        $this->assertEquals($tour->booking_fee ?? 0, $order->booking_fee); // No booking fee was provided, so should clone from tour. Booking Fee is not nullable on order, so should be 0
        $this->assertTrue($order->ordered_on->eq($data['ordered_on'])); // Ordered on dates should match
        $this->assertEquals($customer->id, $order->leadBooker->customer_id); // Lead booker is as provided
    }

    /**
     * @covers \App\Http\Controllers\Admin\Order\OrderController::update
     * @covers \App\Http\Requests\Admin\Order\UpdateOrderRequest
     * @return void
     */
    public function testOrderUpdate(): void
    {
        $order = $this->generateOrder();
        $route = route('orders.update', ['order' => $order->id]);
        $data = [
            'ordered_on' => now()->micro(0),
            'deposit' => 1000,
            'booking_fee' => 1000,
            'internal_notes' => 'Test Internal Notes',
            'external_notes' => 'Test External Notes',
            'invoice_footer' => 'Test Invoice Footer',
        ];

        $this->performFailCases($route, (new Order())->getPermissionSet('update'), ['ordered_on',]);
        $this->performWithEverything($route, $data)->assertRedirectToRoute('orders.view', ['order' => $order->id]);
        $this->assertTrue($this->compareModel($order->refresh(), $data));
    }
}
