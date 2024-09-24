<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Customer\Customer;
use App\Models\Customer\Organization;
use App\Models\System\Brand;
use App\Models\User;
use Carbon\Carbon;

/**
 * @property array $travellers
 */
class Itinerary
{
    /**
     * @param string|null $package
     * @param string|null $event
     * @param string|null $description
     * @param string|null $image
     * @param string|null $reference
     * @param Organization|null $organization
     * @param User|null $consultant
     * @param Carbon $start
     * @param Carbon $end
     * @param ItineraryTraveller $booker
     * @param Brand $brand
     * @param ItineraryTraveller[] $travellers
     * @param array<int, ItineraryItem[]> $items
     * @param ItineraryPaymentDetails|null $finances
     * @param string|null $terms
     * @param string|null $footer
     * @param string|null $notes
     */
    public function __construct(
        public string|null $package,
        public string|null $event,
        public string|null $description,
        public string|null $image,
        public string|null $reference,
        public Organization|null $organization,
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
    )
    {
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
