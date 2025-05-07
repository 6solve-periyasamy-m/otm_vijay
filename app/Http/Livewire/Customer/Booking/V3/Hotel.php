<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
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

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking);
        $this->loadRoomings();
        $this->validateRoomCount(false);
        $this->lead = $this->booking->leadTraveller;
        $bookingRooms = $this->tour->repository->getBookingRooms($this->selectedHotel);
        foreach ($this->rooms as $index => $room) {
            $roomId = $room['room'] ?? array_key_first($bookingRooms);
            $this->roomDescriptions[$index] = $bookingRooms[$roomId]['room_desc'] ?? '';
        }
    }

    public function loadRoomings(): void
    {
        $this->selectedHotel = $this->booking->booking_accommodation_id;
        foreach ($this->booking->groups as $group) {
            if ($group->accommodation()->count() === 0) { continue; }
            $this->rooms[] = ['room' => $group->accommodation()->first()->tourComponent->inventory->room_type_id, 'travellers' => $group?->travellers->count(),];
        }
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
        $this->validate();
        $this->lead->save();
        $this->booking->lead_traveller_id = $this->lead->id;
        $this->booking->save();
        $travellerExcess = $this->getTravellerCount();
        foreach ($this->rooms as $room) {
            $travellerExcess -= RoomType::find($room['room'])?->maximum_occupancy;
        }
        if ($travellerExcess > 0) {
            return $this->addError('common', 'Not all travellers have rooms. Please review number of travellers or the number of rooms selected');
        }
        if ($travellerExcess < 0) {
            return $this->addError('common', 'More travellers have been added to rooms than are travelling');
        }

        return redirect()->route('booking.v3.tickets', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function rules()
    {
        return [
            'rooms.*.room' => 'required|integer',
            'rooms.*.travellers' => 'required|integer|min:1',
        ];
    }


    public function addRoom(): void
    {
        if (count($this->rooms) >= $this->getMaximumRooms()) { return; }
        $room = $this->tour->repository->getDefaultRoom($this->selectedHotel);
        $this->rooms[] = ['room' => $room, 'travellers' => RoomType::find($room)?->maximum_occupancy,];
    }

    public function removeRoom(): void
    {
        if ((count($this->rooms) - 1) < $this->getMinimumRooms()) { return; }
        unset($this->rooms[count($this->rooms) - 1]);
    }


    public function updated($name, $value): void
    {
        \Log::info('Hotel Form Mounted', ['Room' => $name, "Vald" => $value]);
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
}
