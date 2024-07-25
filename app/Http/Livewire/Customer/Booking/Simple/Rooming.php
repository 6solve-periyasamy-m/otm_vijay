<?php

namespace App\Http\Livewire\Customer\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use Livewire\Component;

class Rooming extends Component
{
    public Tour|int $tour;
    public Booking|int|null $booking;
    public BookingTraveller|null $lead = null;

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

    public function render()
    {
        return view('livewire.customer.booking.simple.rooming');
    }

    public function rules()
    {
        return [
            'lead.first_name' => 'required|string',
            'lead.last_name' => 'required|string',
            'lead.email_address' => 'required|email',
            'lead.mobile_number' => 'required|string',
        ];
    }
}
