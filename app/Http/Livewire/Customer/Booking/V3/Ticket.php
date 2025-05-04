<?php

namespace App\Http\Livewire\Customer\Booking\V3;

use App\Http\Livewire\Abstract\V3BookingComponent;
use App\Models\Activity\ActivityInventoryTour;

class Ticket extends V3BookingComponent
{

    protected $listeners = ['currencyUpdated' => 'updateCurrency'];

    public function mount($tour = null, $booking = null, $quote = null)
    {
        parent::mount($tour, $booking, $quote);
    }

    public function back()
    {
        return redirect()->route('booking.v3.hotel', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function advance()
    {
        return redirect()->route('booking.v3.inclusions', ['tour' => $this->tour->booking_form_url, 'booking' => $this->booking->token]);
    }

    public function adjustUpgrade(int $upgradeId): void
    {
        $upgrade = ActivityInventoryTour::find($upgradeId);
        $parent = $upgrade->repository->getUpgradeParent();
        foreach ($this->booking->travellers as $traveller) {
            $found = false;
            foreach ($traveller->activities as $activity) {
                // already owns component
                if ($activity->activity_inventory_tour_id === $upgrade->id) { $found = true; break; }
                // Is already on tree
                if ($parent->repository->hasAsUpgrade($activity->tourComponent)) {
                    $activity->repository->delete();
                    $upgrade->repository->grantToBookingTraveller($traveller);
                    $found = true;
                    break;
                }
            }
            // If upgrade isn't found, then add it anyway
            if (!$found) {
                $upgrade->repository->grantToBookingTraveller($traveller);
            }
        }
    }

    public function render()
    {
        return view('livewire.customer.booking.v3.ticket');
    }
}