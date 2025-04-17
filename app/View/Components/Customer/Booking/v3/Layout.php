<?php

namespace App\View\Components\Customer\Booking\v3;

use App\Models\Booking\Booking;
use App\Models\System\Brand;
use App\Models\Tour\Tour;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{

    public Brand $brand;

    public function __construct(
        public Tour $tour,
        public Booking $booking,
        public int $stage,
    )
    { $this->brand = $this->tour->brand;}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.customer.booking.v3.layout');
    }
}
