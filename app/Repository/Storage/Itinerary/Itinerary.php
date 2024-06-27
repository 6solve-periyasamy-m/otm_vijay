<?php

namespace App\Repository\Storage\Itinerary;

use App\Models\Customer\Customer;
use App\Models\Customer\Organization;
use App\Models\System\Brand;
use Carbon\Carbon;

/**
 * @property array $travellers
 */
class Itinerary
{
    public int $travellerCount;

    /**
     * @param string|null $package
     * @param string|null $event
     * @param string|null $description
     * @param string|null $image
     * @param string|null $reference
     * @param Organization|null $organization
     * @param Carbon $start
     * @param Carbon $end
     * @param Customer $booker
     * @param Brand $brand
     * @param Customer[] $travellers
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
        public Carbon $start,
        public Carbon $end,
        public Customer $booker,
        public Brand $brand,
        public array|int $travellers,
        public array $items,
        public ItineraryPaymentDetails|null $finances,
        public string|null $terms,
        public string|null $footer,
        public string|null $notes,
    )
    {
        $this->setImage($this->image);
        $this->setTravellers($this->travellers);
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
}
