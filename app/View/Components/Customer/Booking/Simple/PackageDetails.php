<?php

namespace App\View\Components\Customer\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Tour\Tour;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PackageDetails extends Component
{
    public Tour $tour;
    public Booking $booking;
    /**
     * Create a new component instance.
     */
    public function __construct(Tour $tour, Booking|null $booking = null)
    {
        $this->booking = $booking;
        $this->tour = $tour;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.customer.booking.simple.package-details');
    }
}
