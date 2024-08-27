<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use App\Repository\Model\Booking\BookingTravellerRepository;
use Livewire\Component;

class Rooming extends Component
{
    private const MAX_TRAVELLERS = 5;

    protected $messages = [
        'rooms.*.room.required' => "This field is required",
        'rooms.*.travellers.required' => "This field is required",
    ];

    public Tour|int $tour;
    public Booking|int|null $booking;
    public BookingTraveller|null $lead = null;
    public array $rooms = [];

    public function mount(Tour|int $tour, Booking|int|null $booking = null): void
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);

        if ($this->booking->tour_id !== null && $this->booking->tour_id !== $this->tour->id) { abort(404); }

        if ($this->booking->id === null) {
            $this->booking = BookingRepository::make($this->tour);
            $this->booking->save();
        }

        $this->lead = $this->booking->leadTraveller ?? BookingTravellerRepository::make([]);

        if ($this->lead->id === null) {
            $this->lead->booking_id = $this->booking->id;
            $this->lead->save();
            $this->booking->lead_traveller_id = $this->lead->id;
            $this->booking->save();
        }
        $this->addTraveller();
        $this->validateRoomCount();
    }

    public function renew()
    {
        $this->booking = Booking::find($this->booking->id);
        $this->lead = $this->booking->leadTraveller;
        $this->tour = Tour::find($this->tour->id);
        $this->rooms = $this->rooms;
        //$this->renewRooming();
    }

    public function getTravellerCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    public function addTraveller(): void
    {
        if ($this->booking->travellers()->count() >= self::MAX_TRAVELLERS) { return; }
        $this->booking->repository->addUnknownTraveller();
        $this->validateRoomCount();
        $this->renew();
        $this->render();
    }

    public function removeTraveller(): void
    {
        $this->booking->repository->removeUnknownTraveller();
        $this->validateRoomCount();
        $this->renew();
        $this->render();
    }

    public function setupRooming(): void
    {
        $this->booking->repository->setupSimpleRooming($this->rooms);
    }

    public function proceed()
    {
        $this->validate();
        $this->lead->save();
        $this->booking->lead_traveller_id = $this->lead->id;
        $this->booking->save();
        $travellerExcess = $this->getTravellerCount();
        foreach ($this->rooms as $room) {
            $travellerExcess -= $room['travellers'];
        }
        if ($travellerExcess > 0) {
            return $this->addError('common', 'Not all travellers have rooms');
        }
        if ($travellerExcess < 0) {
            return $this->addError('common', 'More travellers have been added to rooms than are travelling');
        }
        return redirect()->route('booking.simple.checkout', [
            'token' => $this->booking->token,
            'tour' => $this->tour->booking_form_url,
        ]);
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

    public function addRoom(): void
    {
        if (count($this->rooms) >= $this->getMaximumRooms()) { return; }
        $this->rooms[] = ['room' => $this->tour->repository->getDefaultRoom(), 'travellers' => 2,];
    }

    public function removeRoom(): void
    {
        if ((count($this->rooms) - 1) < $this->getMinimumRooms()) { return; }
        unset($this->rooms[count($this->rooms) - 1]);
    }

    public function validateRoomCount(): void
    {
        foreach ($this->rooms as $i => $iValue) {
            $this->rooms[$i]['travellers'] = (int)$iValue['travellers'];
        }
        for ($i = count($this->rooms); $i < $this->getMinimumRooms(); $i++) {
            $this->rooms[] = ['room' => $this->tour->repository->getDefaultRoom(), 'travellers' => 2,];
        }
        for ($i = count($this->rooms) - 1; $i >= $this->getMaximumRooms(); $i--) {
            unset($this->rooms[$i]);
        }
        $this->setupRooming();
    }

    public function getMinimumRooms(): int
    {
        return (int)ceil($this->getTravellerCount() / 2);
    }

    public function getMaximumRooms(): int
    {
        return $this->getTravellerCount();
    }

    public function render()
    {
        return view('livewire.customer.booking.simple.rooming');
    }

    public function rules()
    {
        return [
            'lead.first_name' => 'required|string',
            'lead.last_name' => 'required|string',
            'lead.email_address' => 'required|email:rfc,dns',
            'lead.mobile_number' => 'required|phone:INTERNATIONAL',
            'rooms.*.room' => 'required|integer',
            'rooms.*.travellers' => 'required|integer|min:1',
        ];
    }
}
