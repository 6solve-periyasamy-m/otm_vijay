<?php

namespace App\View\Components\Customer\Booking\Simple;

use App\Models\Booking\Booking;
use App\Models\Tour\Tour;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Settings;

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
    public function getCurrency()
    {
        return $this->booking->currency ?? Settings::currency();
    }

    public function getFXRate(): float
    {
        return Settings::getConversionRate(Settings::currency(), $this->getCurrency());
    }

    public function formatCurrency(int|float|null $value, bool $round = true): string
    {
        $value = $value ?? 0.0;
        $value *= $this->getFXRate();
        if ($round && flag('booking.round_to_five')) {
            $value = round_to_five($value);
        }
        return f_currency_booking($value, $this->getCurrency(), true) . " " . $this->getCurrency()->code;
    }
}
