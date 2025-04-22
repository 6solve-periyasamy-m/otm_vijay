<?php

namespace App\Http\Livewire\Abstract;

use App\Models\Booking\Booking;
use App\Models\System\Brand;
use App\Models\Tour\Tour;
use Livewire\Component;

abstract class V3BookingComponent extends Component
{
    public Tour|int|null $tour;
    public Booking|int|null $booking;
    public Brand $brand;

    public function mount(Tour|int|null $tour = null, Booking|int|null $booking = null)
    {
        $this->tour = Tour::getForMount($tour);
        $this->booking = Booking::getForMount($booking);
        $this->brand = $this->tour->brand ?? Brand::getSystemBrand();
    }

    abstract public function back();
    abstract public function advance();
}