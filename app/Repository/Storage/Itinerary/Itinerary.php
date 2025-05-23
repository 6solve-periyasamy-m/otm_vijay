<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Customer\Agent;
use App\Models\Customer\Organization;
use App\Models\System\Brand;
use App\Models\Tour\Event;
use App\Models\User;
use Carbon\Carbon;

/**
 * @property array $travellers
 */
class Itinerary
{
    /**
     * @param string|null $package
     * @param Event|null $event
     * @param string|null $description
     * @param string|null $image
     * @param string|null $banner
     * @param string|null $reference
     * @param Organization|null $organization
     * @param Agent|null $agent
     * @param User|null $consultant
     * @param Carbon $start
     * @param Carbon $end
     * @param Carbon $created
     * @param ItineraryTraveller $booker
     * @param Brand $brand
     * @param ItineraryTraveller[] $travellers
     * @param array<int, ItineraryItem[]> $items
     * @param ItineraryPaymentDetails|null $finances
     * @param string|null $terms
     * @param string|null $footer
     * @param string|null $notes
     * @param string|null $payment_details
     */
    public function __construct(
        public string|null $package,
        public Event|null $event,
        public string|null $description,
        public string|null $image,
        public string|null $banner,
        public string|null $reference,
        public Organization|null $organization,
        public Agent|null $agent,
        public User|null $consultant,
        public Carbon $start,
        public Carbon $end,
        public Carbon $created,
        public ItineraryTraveller $booker,
        public Brand $brand,
        public array $travellers,
        public array $items,
        public ItineraryPaymentDetails|null $finances,
        public string|null $terms,
        public string|null $footer,
        public string|null $notes,
        public string|null $payment_details,
    )
    {
        $this->setBanner($banner ?? $this->image);
        $this->setImage($this->image);
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

    public function setBanner(string|null $banner): Itinerary
    {
        if (isset($banner)) {
            $this->banner = img_to_b64($banner);
        } else {
            $this->banner = img_to_b64('images/default_image.png');
        }
        return $this;
    }
    
    public function getPayingCount(): int
    {
        $paying = 0 + $this->booker->paying;
        foreach ($this->travellers as $traveller) { $paying += $traveller->paying; }
        return $paying;
    }
    
    public function getTravellingCount(): int
    {
        $travelling = 0 + $this->booker->travelling;
        foreach ($this->travellers as $traveller) { $travelling += $traveller->travelling; }
        return $travelling;
    }
}
