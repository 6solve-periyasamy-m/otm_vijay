<?php

namespace Tests\Traits;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Customer\Customer;
use App\Models\Tour\Tour;
use App\Repository\Model\Booking\BookingRepository;
use App\Repository\Model\Booking\BookingTravellerRepository;

trait TestsBooking
{
    use TestsTour;

    public function generateBooking(Tour $tour = null, Customer $customer = null): Booking
    {
        $tour = $tour ?? $this->generateTour();
        $customer = $customer ?? Customer::factory()->create();
        return BookingRepository::create($tour, BookingTraveller::make(['customer_id' => $customer->id]));
    }

    public function generateBookingTraveller(Booking $booking = null, Customer $customer = null)
    {
        $booking = $booking ?? $this->generateBooking();
        $customer = $customer ?? Customer::factory()->create();
        return BookingTravellerRepository::create($booking, ['customer_id' => $customer->id]);
    }
}
