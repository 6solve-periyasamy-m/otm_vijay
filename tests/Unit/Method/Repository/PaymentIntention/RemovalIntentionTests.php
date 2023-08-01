<?php

namespace Method\Repository\PaymentIntention;

use App\Repository\Intention\PaymentIntentionRepository;
use App\Repository\Intention\Storage\RemovalIntention;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

class RemovalIntentionTests extends DatabaseTestCase
{
    use TestsOrder;

    public function testRemovalOfAccommodation()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateAccommodationInventoryTour($order->tour)->repository;
        $component->grantToCustomer($lead);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [RemovalIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNull($component->getOrderComponent($lead));
    }

    public function testRemovalOfActivity()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateActivityInventoryTour($order->tour)->repository;
        $component->grantToCustomer($lead);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [RemovalIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNull($component->getOrderComponent($lead));
    }

    public function testRemovalOfFlight()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateFlightInventoryTour($order->tour)->repository;
        $component->grantToCustomer($lead);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [RemovalIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNull($component->getOrderComponent($lead));
    }

    public function testRemovalOfTransport()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateTransportInventoryTour($order->tour)->repository;
        $component->grantToCustomer($lead);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [RemovalIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNull($component->getOrderComponent($lead));
    }

    public function testRemovalOfMultiple()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $accommodation = $this->generateAccommodationInventoryTour($order->tour)->repository;
        $activity = $this->generateActivityInventoryTour($order->tour)->repository;
        $flight = $this->generateFlightInventoryTour($order->tour)->repository;
        $transport = $this->generateTransportInventoryTour($order->tour)->repository;
        $accommodation->grantToCustomer($lead);
        $activity->grantToCustomer($lead);
        $flight->grantToCustomer($lead);
        $transport->grantToCustomer($lead);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [
            RemovalIntention::create($lead, $accommodation),
            RemovalIntention::create($lead, $activity),
            RemovalIntention::create($lead, $flight),
            RemovalIntention::create($lead, $transport),
            ]);
        $intention->repository->process();

        $this->assertNull($accommodation->getOrderComponent($lead));
        $this->assertNull($activity->getOrderComponent($lead));
        $this->assertNull($flight->getOrderComponent($lead));
        $this->assertNull($transport->getOrderComponent($lead));
    }

}
