<?php

namespace Method\Repository\PaymentIntention;

use App\Repository\Intention\PaymentIntentionRepository;
use App\Repository\Intention\Storage\UpgradeIntention;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsOrder;

class UpgradeIntentionTests extends DatabaseTestCase
{
    use TestsOrder;

    public function testUpgradeOfAccommodation()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateAccommodationInventoryTour($order->tour);
        $component->repository->grantToCustomer($lead);
        $upgrade = $this->generateAccommodationUpgrade($component);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [UpgradeIntention::create($lead, $component->repository, $upgrade->repository),]);
        $intention->repository->process();

        $this->assertNull($component->repository->getOrderComponent($lead));
        $this->assertNotNull($upgrade->repository->getOrderComponent($lead));
    }

    public function testUpgradeOfActivity()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateActivityInventoryTour($order->tour);
        $component->repository->grantToCustomer($lead);
        $upgrade = $this->generateActivityUpgrade($component);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [UpgradeIntention::create($lead, $component->repository, $upgrade->repository),]);
        $intention->repository->process();

        $this->assertNull($component->repository->getOrderComponent($lead));
        $this->assertNotNull($upgrade->repository->getOrderComponent($lead));
    }

    public function testUpgradeOfFlight()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateFlightInventoryTour($order->tour);
        $component->repository->grantToCustomer($lead);
        $upgrade = $this->generateFlightUpgrade($component);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [UpgradeIntention::create($lead, $component->repository, $upgrade->repository),]);
        $intention->repository->process();

        $this->assertNull($component->repository->getOrderComponent($lead));
        $this->assertNotNull($upgrade->repository->getOrderComponent($lead));
    }

    public function testUpgradeOfTransport()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $component = $this->generateTransportInventoryTour($order->tour);
        $component->repository->grantToCustomer($lead);
        $upgrade = $this->generateTransportUpgrade($component);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [UpgradeIntention::create($lead, $component->repository, $upgrade->repository),]);
        $intention->repository->process();

        $this->assertNull($component->repository->getOrderComponent($lead));
        $this->assertNotNull($upgrade->repository->getOrderComponent($lead));
    }

    public function testUpgradeOfMultiple()
    {
        $order = $this->generateOrder();
        $lead = $order->leadBooker;
        $accommodation = $this->generateAccommodationInventoryTour($order->tour);
        $accommodation->repository->grantToCustomer($lead);
        $accommodationUpgrade = $this->generateAccommodationUpgrade($accommodation);
        $activity = $this->generateActivityInventoryTour($order->tour);
        $activity->repository->grantToCustomer($lead);
        $activityUpgrade = $this->generateAccommodationUpgrade($accommodation);
        $flight = $this->generateFlightInventoryTour($order->tour);
        $flight->repository->grantToCustomer($lead);
        $flightUpgrade = $this->generateAccommodationUpgrade($accommodation);
        $transport = $this->generateTransportInventoryTour($order->tour);
        $transport->repository->grantToCustomer($lead);
        $transportUpgrade = $this->generateAccommodationUpgrade($accommodation);

        $intention = PaymentIntentionRepository::create($order, $lead->customer, 'Installment', [
            UpgradeIntention::create($lead, $accommodation->repository, $accommodationUpgrade->repository),
            UpgradeIntention::create($lead, $activity->repository, $activityUpgrade->repository),
            UpgradeIntention::create($lead, $flight->repository, $flightUpgrade->repository),
            UpgradeIntention::create($lead, $transport->repository, $transportUpgrade->repository),
            ]);
        $intention->repository->process();

        $this->assertNull($accommodation->repository->getOrderComponent($lead));
        $this->assertNull($activity->repository->getOrderComponent($lead));
        $this->assertNull($flight->repository->getOrderComponent($lead));
        $this->assertNull($transport->repository->getOrderComponent($lead));

        $this->assertNotNull($accommodationUpgrade->repository->getOrderComponent($lead));
        $this->assertNotNull($activityUpgrade->repository->getOrderComponent($lead));
        $this->assertNotNull($flightUpgrade->repository->getOrderComponent($lead));
        $this->assertNotNull($transportUpgrade->repository->getOrderComponent($lead));
    }

}
