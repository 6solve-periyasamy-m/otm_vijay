<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Helper\Enum\BookingTravellerRole;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use Livewire\Component;

class Rooming extends Component
{
    public Tour|int $tour;
    public Booking|int|null $booking;
    public BookingTraveller|null $lead = null;
    public array $rooms = [];

    public function mount(Tour|int $tour, Booking|int|null $booking = null): void
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);
        $this->lead = $this->booking->leadTraveller ?? new BookingTraveller();

        if ($this->booking->tour_id !== null && $this->booking->tour_id !== $this->tour->id) { abort(404); }

        if ($this->booking->id === null) {
            $this->booking = BookingRepository::make($this->tour);
            $this->booking->save();
        }

        if ($this->lead->id === null) {
            $this->lead->booking_id = $this->booking->id;
            $this->lead->save();
            $this->booking->lead_traveller_id = $this->lead->id;
            $this->booking->save();
        }
        $this->validateRoomCount();
    }

    public function getTravellerCount(): int
    {
        return $this->booking->travellers()->where('role', '!=', BookingTravellerRole::NOT_TRAVELLING)->count();
    }

    public function addTraveller(): void
    {
        if ($this->booking->travellers()->count() >= 7) { return; }
        $this->booking->repository->addUnknownTraveller();
        $this->validateRoomCount();
        $this->render();
    }

    public function removeTraveller(): void
    {
        $this->booking->repository->removeUnknownTraveller();
        $this->validateRoomCount();
        $this->render();
    }

    public function proceed()
    {
        // TODO: Implement Rooming
        $this->validate();
        $this->lead->save();
        $this->booking->lead_traveller_id = $this->lead->id;
        $this->booking->save();
        return redirect()->route('booking.simple.checkout', [
            'token' => $this->booking->token,
            'tour' => $this->tour->booking_form_url,
        ]);
    }

    public function addRoom(): void
    {
        if (count($this->rooms) >= $this->getMaximumRooms()) { return; }
        $this->rooms[] = ['room' => null, 'travellers' => null,];
    }

    public function removeRoom(): void
    {
        if ((count($this->rooms) - 1) < $this->getMinimumRooms()) { return; }
        unset($this->rooms[count($this->rooms) - 1]);
    }

    public function validateRoomCount(): void
    {
        for ($i = count($this->rooms); $i < $this->getMinimumRooms(); $i++) {
            $this->rooms[] = ['room' => null, 'travellers' => null,];
        }
        for ($i = count($this->rooms) - 1; $i >= $this->getMaximumRooms(); $i--) {
            unset($this->rooms[$i]);
        }
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
