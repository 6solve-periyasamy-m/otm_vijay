<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\RoomCategory;
use App\Models\Accommodation\RoomType;
use App\Models\Booking\BookingTraveller;

class Hotel extends V3BookingComponent
{    
    public $listeners = ['currencyUpdated' => 'updateCurrency', 'advance'];
    protected $messages = [
        'rooms.*.room.required' => "This field is required",
        'rooms.*.travellers.required' => "This field is required",
    ];
    public BookingTraveller|null $lead = null;
    public $roomDescriptions = [];
    public $categories = [];
    public $reselectConfigMessage = '';

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking);
        $this->loadRoomings();
        $this->validateRoomCount(false);
        $this->lead = $this->booking->leadTraveller;
        $this->setRoomDescriptions($this->selectedHotel);
        $this->setupCategories();
        $this->reselectConfigMessage = '';
    }

    private function setupCategories()
    {
        foreach ($this->tour->repository->getHotelGroups() as $hotel => $groups) {
            $this->categories[$hotel] = $groups[array_key_first($groups)]?->category?->id;
        }
    }

    public function checkRooms(): bool
    {
        $valid = true;
        foreach ($this->rooms as $key => $room) {
            $type = RoomType::find($room['room']);
            if ($type === null) {
                $this->addError('rooms.' . $key . '.travellers', 'Invalid room type');
                $valid = false;
            } else {
                if ($type->maximum_occupancy < $room['travellers']) {
                    $this->addError('rooms.' . $key . '.travellers', 'Too many travellers for room size');
                    $valid = false;
                } elseif ($type->maximum_occupancy > $room['travellers']) {
                    // Disabled for now. Too few travellers is fine.
                    // $this->addError('rooms.' . $key . '.travellers', 'Too few travellers for room size');
                    // $valid = false;
                }
            }
        }
        return $valid;
    }

    public function loadRoomings(): void
    {
        $this->selectedHotel = $this->booking->booking_accommodation_id;
        foreach ($this->booking->groups as $group) {
            if ($group->accommodation()->count() === 0) { continue; }
            $this->rooms[] = ['room' => $group->accommodation()->first()->tourComponent->inventory->room_type_id, 'travellers' => $group?->travellers->count(),];
        }
        $this->renew();
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.hotel');
    }

    public function back()
    {
        return redirect()->route('booking.v3.guest', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        foreach ($this->rooms as $i => $room) {
            if ($room['room'] === null || $room['travellers'] === null) { return $this->addError('common', 'Please select rooming configuration'); }
        }
        if (!$this->checkRooms()) {
            return $this->addError('common', 'There are issues with the room assignments, please double check them.');
        }
        $this->validate();
        $this->lead->save();
        $this->booking->lead_traveller_id = $this->lead->id;
        $this->booking->save();
        $travellerExcess = $this->getTravellerCount();
        foreach ($this->rooms as $room) {
            $travellerExcess -= $room['travellers'];
        }
        if ($travellerExcess > 0) {
            return $this->addError('common', 'Not all travellers have rooms. Please review number of travellers or the number of rooms selected');
        }
        if ($travellerExcess < 0) {
            return $this->addError('common', 'More travellers have been added to rooms than are travelling');
        }
        $this->booking->updateBookingProgressNotification('Hotel');
        return redirect()->route('booking.v3.tickets', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function rules()
    {
        return [
            'rooms.*.room' => 'required|integer',
            'rooms.*.travellers' => 'required|integer|min:1',
        ];
    }

    public function calculateUpgradeCost(int $hotel, int|null $category = null)
    {
        $groups = $this->tour->repository->getHotelGroups()[$hotel];
        $hotel = Accommodation::find($hotel);
        $category = RoomCategory::find($category ?? $this->categories[$hotel->id]);
        $cost = 0;
        foreach ($this->rooms as $room) {
            $roomType = RoomType::find($room['room']);
            $equivalent = $this->getEquivalentRoomType($hotel, $category, $roomType);
            foreach ($groups as $group) {
                if ($group->category?->id === $category?->id &&
                    $group->occupancy->id === $equivalent?->id)
                {
                    $cost += $group->getUpgradeCost();
                    break;
                }
            }
        }
        return $cost;
    }

    protected function getEquivalentRoomType(Accommodation $hotel, RoomCategory|null $category, RoomType|null $roomType): RoomType|null
    {
        if ($roomType === null) { return null; }
        $hotelGroups = $this->tour->repository->getHotelGroups()[$hotel->id];
        foreach ($hotelGroups as $hotelGroup) {
            if ($category?->id !== $hotelGroup->category?->id) { continue; }
            if ($roomType->id === $hotelGroup->occupancy->id) { return $hotelGroup->occupancy; }
        }
        $matchType = match (true) {
            str_contains(strtolower($roomType->name), 'twin') => 'twin',
            str_contains(strtolower($roomType->name), 'double') => 'double',
            str_contains(strtolower($roomType->name), 'single') => 'single',
            str_contains(strtolower($roomType->name), 'triple') => 'triple',
            default => strtolower($roomType->name),
        };
        foreach ($hotelGroups as $hotelGroup) {
            if ($category?->id !== $hotelGroup->category?->id) { continue; }
            if (str_contains(strtolower($hotelGroup->occupancy->name), $matchType)) { return $hotelGroup->occupancy; }
        }
        return null;
    }

    public function addRoom(): void
    {
        if (count($this->rooms) >= $this->getMaximumRooms()) { return; }
        //$room = $this->tour->repository->getDefaultRoom($this->selectedHotel);
        $this->rooms[] = ['room' => null, 'travellers' => null,];
        if (count($this->rooms) === $this->getTravellingCount()) {
            foreach ($this->rooms as $key => $room) {
                $this->rooms[$key] = ['room' => $room['room'], 'travellers' => 1,];
            }
        }
        $this->renew();
    }

    public function removeRoom(): void
    {
        if ((count($this->rooms) - 1) < $this->getMinimumRooms()) { return; }
        unset($this->rooms[count($this->rooms) - 1]);
        $this->renew();
    }


    public function updated($name, $value): void
    {
        $this->validateOnly($name);
        $this->booking->save();
        $this->lead->save();
        $this->validateRoomCount();
        $this->renew();
        $this->render();
    }

    public function setHotel($id): void
    {
        $this->selectedHotel = $id;
        $this->setupRooming();
        $this->setRoomDescriptions($id);

        $this->reselectConfigMessage = 'Please reselect your configuration.';
        $this->dispatchBrowserEvent('scroll-to-reselect');
    }

    public function updatedRooms($value, $key)
    {
        if (str_ends_with($key, '.room')) {
            $index = explode('.', $key)[0];
            $roomId = $value;
            $bookingRooms = $this->tour->repository->getBookingRooms($this->selectedHotel);
            $this->roomDescriptions[$index] = $bookingRooms[$roomId]['room_desc'] ?? '';
        }
    }

    private function setRoomDescriptions($hotelId)
    {
        $bookingRooms = $this->tour->repository->getBookingRooms($hotelId);
        foreach ($this->rooms as $index => $room) {
            $roomId = $room['room'] ?? array_key_first($bookingRooms);
            $this->roomDescriptions[$index] = $bookingRooms[$roomId]['room_desc'] ?? '';
        }
    }

    public function hasHotelUpgrades(): bool
    {
        $hotelGroups = $this->tour->repository->getHotelGroups();

        foreach ($hotelGroups as $groups) {
            foreach ($groups as $group) {
                foreach ($group->rooms as $room) {
                    if ($room->tour_component_type === 'Upgrade') {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
