<?php

namespace Tests\Unit\Method\Repository\Order;

use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Repository\Storage\ConvertedCustomer;
use App\Repository\Storage\Quote\CustomerForConversion;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsBooking;
use Tests\Traits\Model\TestsOrder;
use Tests\Traits\Model\TestsQuote;

class OrderForceDeleteTest extends DatabaseTestCase
{
    use TestsBooking, TestsOrder, TestsQuote;

    public function testManuallyCreatedDeletion()
    {
        $order = $this->generateOrder();
        $id = $order->id;
        $order->repository->forceDelete();
        $this->assertNull(Order::withTrashed()->find($id));
    }

    public function testConvertedBookingDeletion()
    {
        $booking = $this->generateBooking();
        $order = $booking->repository->convertToOrder();
        $id = $order->id;
        $order->repository->forceDelete();
        $this->assertNull(Order::withTrashed()->find($id));
    }

    public function testConvertedQuoteDeletion()
    {
        $quote = $this->generateQuote();
        $quote->repository->addPricePoint(1, 100);
        $customer = new CustomerForConversion(true, true);
        $customer->customer = Customer::factory()->create()->id;
        $order = $quote->repository->convert($customer, []);
        $id = $order->id;
        $order->repository->forceDelete();
        $this->assertNull(Order::withTrashed()->find($id));
    }
}
