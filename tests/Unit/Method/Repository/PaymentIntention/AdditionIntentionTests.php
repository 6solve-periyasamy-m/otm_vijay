<?php

namespace Method\Repository\PaymentIntention;

use App\Repository\Intention\PaymentIntentionRepository;
use App\Repository\Intention\Storage\AdditionIntention;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsOrder;

class AdditionIntentionTests extends DatabaseTestCase
{
    use TestsOrder;

    public function testAdditionOfAccommodation()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateAccommodationInventoryTour($order->tour)->repository;

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [AdditionIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNotNull($component->getOrderComponent($lead));
    }

    public function testAdditionOfActivity()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateActivityInventoryTour($order->tour)->repository;

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [AdditionIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNotNull($component->getOrderComponent($lead));
    }

    public function testAdditionOfFlight()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateFlightInventoryTour($order->tour)->repository;

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [AdditionIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNotNull($component->getOrderComponent($lead));
    }

    public function testAdditionOfTransport()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateTransportInventoryTour($order->tour)->repository;

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [AdditionIntention::create($lead, $component),]);
        $intention->repository->process();

        $this->assertNotNull($component->getOrderComponent($lead));
    }

    public function testAdditionOfMultiple()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $accommodation = $this->generateAccommodationInventoryTour($order->tour)->repository;
        $activity = $this->generateActivityInventoryTour($order->tour)->repository;
        $flight = $this->generateFlightInventoryTour($order->tour)->repository;
        $transport = $this->generateTransportInventoryTour($order->tour)->repository;

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [
            AdditionIntention::create($lead, $accommodation),
            AdditionIntention::create($lead, $activity),
            AdditionIntention::create($lead, $flight),
            AdditionIntention::create($lead, $transport),
            ]);
        $intention->repository->process();

        $this->assertNotNull($accommodation->getOrderComponent($lead));
        $this->assertNotNull($activity->getOrderComponent($lead));
        $this->assertNotNull($flight->getOrderComponent($lead));
        $this->assertNotNull($transport->getOrderComponent($lead));
    }

}
