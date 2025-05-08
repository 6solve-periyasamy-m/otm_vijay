<?php

namespace App\Repository\Model\Booking;

use App\Exceptions\RoomingFailedException;
use App\Models\Accommodation\AccommodationInventoryTour;
use App\Models\Accommodation\RoomType;
use App\Models\Booking\BookingGroup;
use App\Models\Booking\BookingTraveller;
use App\Models\Booking\Component\BookingAccommodation;
use App\Models\Tour\Tour;
use App\Repository\Abstracts\ModelRepository;
use App\Repository\RoomingRepository;

class BookingGroupRepository extends ModelRepository
{
    private BookingGroup $group;

    public function __construct(BookingGroup $group)
    {
        $this->group = $group;
    }

    public function addTravellerToGroup(BookingTraveller $traveller): void
    {
        $this->group->travellers()->attach($traveller);
    }

    /**
     * @throws RoomingFailedException
     */
    public function addTemplatesOfTypeToGroup(Tour $tour, RoomType $roomType, bool $failOnMissing = false, bool $strictTyping = false)
    {
        foreach (RoomingRepository::getTemplateTourInventory($tour) as $template) {
            $roomWithType = RoomingRepository::getInventoryWithRoomType($template, $roomType);
            if (!isset($roomWithType) && !$strictTyping) {
                foreach (RoomingRepository::getHydratedRoomTypesForInventory($template) as $availableType) {
                    if ($availableType->maximum_occupancy === $roomType->maximum_occupancy) {
                        $roomWithType = RoomingRepository::getInventoryWithRoomType($template, $availableType);
                        if (isset($roomWithType)) break;
                    }
                }
            }
            if (isset($roomWithType)) {
                $this->addRoomToGroup($roomWithType);
            } elseif ($failOnMissing) {
                throw new RoomingFailedException(
                    "Room not found for template {$template->id} with type {$roomType->id}" .
                    ($strictTyping ? "" : " or with a size of {$roomType->maximum_occupancy}")
                );
            }
        }
    }

    public function addRoomToGroup(AccommodationInventoryTour $inventoryTour): BookingAccommodation
    {
        return BookingAccommodation::create([
            'booking_group_id' => $this->group->id,
            'accommodation_inventory_tour_id' => $inventoryTour->id
        ]);
    }

    public function update(array $data): BookingGroup
    {
        $this->group->update($data);
        $this->save();
        return $this->get();
    }

    public function save(): bool
    {
        return $this->group->save();
    }

    public function get(): BookingGroup
    {
        return $this->group;
    }

    public function delete(): bool
    {
        return $this->group->delete();
    }

    public function isDeleted(): bool
    {
        return !isset($this->group);
    }

    public function __toString(): string
    {
        return $this->getTravellersAsString();
    }

    public function getTravellersAsString($delimiter = ', '): string
    {
        $customers = "";
        foreach ($this->group->travellers as $traveller) {
            $customers .= "{$traveller->first_name} {$traveller->last_name}{$delimiter}";
        }
        return substr($customers, 0, -1 * strlen($delimiter));
    }

    public static function find($id): BookingGroup|null
    {
        return BookingGroup::find($id);
    }

    public function getTotalCost(): float
    {
        $cost = 0;
        foreach ($this->group->accommodation as $room) {
            if ($room->tourComponent->tour_component_type !== 'Included') {
                $cost += $room->tourComponent->tour_sales_price;
            }
        }
        return $cost;
    }
}
