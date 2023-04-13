<?php

namespace App\Http\Livewire\Customer\Booking\Form;

use App\Models\Accommodation\RoomType;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Repository\RoomingRepository;
use LivewireUI\Modal\ModalComponent;

class AdditionalTraveller extends ModalComponent
{
    public Booking|int $booking;
    public BookingTraveller|int|null $traveller;
    public $room_type;
    public $group;

    public function mount(Booking|int $booking, BookingTraveller|int|null $traveller = null)
    {
        if ($booking instanceof Booking) {
            $this->booking = $booking;
        } else {
            $this->booking = Booking::find($booking);
        }
        \Log::info($traveller ?? 'Empty');
        $this->traveller = $traveller ?? new BookingTraveller(['booking_id' => $this->booking->id]);
        \Log::info($this->traveller);
        $this->room_type = $this->traveller?->room_type_id ?? $this->getDefaultRoomType()?->id;
        $this->group = $this->traveller?->group_id ?? 1;
    }

    public function save()
    {
        $this->traveller->booking_id = $this->booking->id;
        $this->traveller->repository->formSave($this->room_type, $this->group);
        $this->emit('travellerAdded', $this->traveller);
        $this->closeModal();
    }

    protected function getAvailableRooms(): array
    {
        return RoomingRepository::getAvailableRoomTypes($this->booking->tour);
    }

    private function getDefaultRoomType(): RoomType|null
    {
        $rooms = $this->getAvailableRooms();
        if (sizeof($rooms)) {
            return $rooms[0];
        }
        return null;
    }

    public function render()
    {
        return view('livewire.customer.booking.form.additional-traveller');
    }

    public function rules()
    {
        return [
            'traveller.title' => 'nullable',
            'traveller.first_name' => 'required',
            'traveller.middle_names' => 'nullable',
            'traveller.last_name' => 'required',
            'traveller.date_of_birth' => 'nullable|date',
            'traveller.email_address' => 'nullable',
            'traveller.mobile_number' => 'nullable',
        ];
    }
}
