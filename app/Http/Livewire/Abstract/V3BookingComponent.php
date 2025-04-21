<?php

namespace App\Http\Livewire\Abstract;

use App\Models\Booking\Booking;
use App\Models\System\Brand;
use App\Models\Tour\Tour;
use Livewire\Component;
use App\Models\Booking\BookingTraveller;
use App\Repository\Model\Booking\BookingTravellerRepository;

abstract class V3BookingComponent extends Component
{
    public Tour|int|null $tour;
    public Booking|int|null $booking;
    public Brand $brand;
    public BookingTraveller|null $lead = null;

    public function mount(Tour|int|null $tour = null, Booking|int|null $booking = null)
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);
        $this->brand = $this->tour->brand ?? Brand::getSystemBrand();
        $this->lead = $this->booking->leadTraveller ?? BookingTravellerRepository::make([]);

        if ($this->lead->id === null) {
            $this->lead->booking_id = $booking->id;
            $this->lead->save();
            $booking->lead_traveller_id = $this->lead->id;
            $booking->save();
        }
    }

    abstract public function back();
    abstract public function advance();
}