<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;

class Ticket extends V3BookingComponent
{
    public array $ticketUpgrades = [];

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);
        $this->setupTicketUpgrades();
    }

    public function setupTicketUpgrades(): void
    {
        foreach ($this->tour->activityInventoryTours()->where('tour_component_type', '=', 'Included')->get() as $tourComponent) {
            $activeUpgrade = $tourComponent->repository->getActiveUpgrade($this->booking->leadTraveller)?->get() ?? $tourComponent;
            $this->ticketUpgrades["{$tourComponent->id}"] = $activeUpgrade->id;
        }
    }

    public function upgradeActivity($id)
    {
        if (array_key_exists($id, $this->ticketUpgrades)) {
            $this->adjustActivityUpgrade($this->ticketUpgrades[$id]);
        }
    }

    public function back()
    {
        return redirect()->route('booking.v3.hotel', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        $this->booking->updateBookingProgressNotification('Tickets');
        $this->booking->last_page = 'Tickets';
        $this->booking->save();
        if ($this->hasInclusions()) {
            return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
        } else {
            return redirect()->route('booking.v3.details', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
        }
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.ticket');
    }
}