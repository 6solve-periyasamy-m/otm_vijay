<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Accommodation\RoomType;
use App\Models\Booking\BookingTraveller;

class Hotel extends V3BookingComponent
{
    public array $rooms = [];
    protected $listeners = ['currencyUpdated' => 'updateCurrency', 'advance'];

    protected $messages = [
        'rooms.*.room.required' => "This field is required",
        'rooms.*.travellers.required' => "This field is required",
    ];
    public BookingTraveller|null $lead = null;

    public function mount($tour = null, $booking = null)
    {
        parent::mount($tour, $booking);
        $this->validateRoomCount();
        $this->lead = $this->booking->leadTraveller;
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
        $travellerExcess = $this->getTravellerCount();
        foreach ($this->rooms as $room) {
            $travellerExcess -= RoomType::find($room['room'])?->maximum_occupancy;
        }
        if ($travellerExcess > 0) {
            dd('Not all travellers have rooms', $travellerExcess);
        }
        if ($travellerExcess < 0) {
            dd('More travellers have been added to rooms than are travelling', $travellerExcess);
        }
        return redirect()->route('booking.v3.guest', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
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
        $this->validateOnly($name);
        $this->booking->save();
        $this->lead->save();
        $this->validateRoomCount();
        //$this->renew();
        $this->render();
    }

}
