<?php

namespace App\View\Components\Customer\Booking\Simple;

use App\Http\Gateways\StripeGateway;
use App\Models\Booking\Booking;
use App\Models\Location\Currency;
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

    public function getSurchargeAmount(bool $payFull): float|null
    {
        $amount = $payFull ? $this->booking->repository->getTotalCost() : $this->booking->repository->getDueTodayAmount();
        return StripeGateway::getAmountForSurcharge($this->booking->currency, $amount * 100) / 100;
    }

    public function getCurrency(): Currency
    {
        return $this->booking->repository->getCurrency();
    }

    public function getFXRate(): float
    {
        return $this->booking->repository->getFXRate();
    }

    public function formatCurrency(int|float|null $value, int $decimalPrecision = 0): string
    {
        return f_currency_booking($value, $this->getCurrency(), $this->getFXRate(), $decimalPrecision);
    }
}
