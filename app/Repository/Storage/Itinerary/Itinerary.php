<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Customer\Customer;
use App\Models\System\Brand;
use Carbon\Carbon;

/**
 * @property array $travellers
 */
class Itinerary
{
    public int $travellerCount;

    /**
     * @param string $package
     * @param string|null $event
     * @param string|null $description
     * @param string|null $image
     * @param string|null $reference
     * @param Carbon $start
     * @param Carbon $end
     * @param Customer $booker
     * @param Brand $brand
     * @param Customer[] $travellers
     * @param array<int, ItineraryItem[]> $items
     * @param ItinerarySchedule[] $schedule
     * @param ItineraryPayment[] $payments
     * @param string|null $terms
     * @param string|null $footer
     * @param string|null $paymentDetails
     * @param string|null $notes
     */
    public function __construct(
        public string $package,
        public string|null $event,
        public string|null $description,
        public string|null $image,
        public string|null $reference,
        public Carbon $start,
        public Carbon $end,
        public Customer $booker,
        public Brand $brand,
        public array|int $travellers,
        public array $items,
        public array $schedule,
        public array $payments,
        public string|null $terms,
        public string|null $footer,
        public string|null $paymentDetails,
        public string|null $notes,
    )
    {
        $this->setImage($this->image);
        $this->setTravellers($this->travellers);
    }

    public function setPackage(string $package): Itinerary
    {
        $this->package = $package;
        return $this;
    }

    public function setEvent(?string $event): Itinerary
    {
        $this->event = $event;
        return $this;
    }

    public function setImage(string|null $image): Itinerary
    {
        if (isset($image)) {
            $this->image = img_to_b64($image);
        } else {
            $this->image = img_to_b64('images/default_image.png');
        }
        return $this;
    }

    public function setReference(?string $reference): Itinerary
    {
        $this->reference = $reference;
        return $this;
    }

    public function setStart(Carbon $start): Itinerary
    {
        $this->start = $start;
        return $this;
    }

    public function setEnd(Carbon $end): Itinerary
    {
        $this->end = $end;
        return $this;
    }

    public function setBooker(Customer $booker): Itinerary
    {
        $this->booker = $booker;
        return $this;
    }

    public function setBrand(Brand $brand): Itinerary
    {
        $this->brand = $brand;
        return $this;
    }

    public function setTravellers(array|int $travellers): Itinerary
    {
        if (is_int($travellers)) {
            $this->travellerCount = $travellers;
            $this->travellers = [];
        } else {
            $this->travellers = $travellers;
            $this->travellerCount = count($this->travellers);
        }
        return $this;
    }

    public function setItems(array $items): Itinerary
    {
        $this->items = $items;
        return $this;
    }

    public function setSchedule(array $schedule): Itinerary
    {
        $this->schedule = $schedule;
        return $this;
    }

    public function setPayments(array $payments): Itinerary
    {
        $this->payments = $payments;
        return $this;
    }

    public function setTerms(?string $terms): Itinerary
    {
        $this->terms = $terms;
        return $this;
    }

    public function setFooter(?string $footer): Itinerary
    {
        $this->footer = $footer;
        return $this;
    }

    public function setPaymentDetails(?string $paymentDetails): Itinerary
    {
        $this->paymentDetails = $paymentDetails;
        return $this;
    }
}
