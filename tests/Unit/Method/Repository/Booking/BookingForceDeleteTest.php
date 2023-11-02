<?php

namespace Tests\Unit\Method\Repository\Booking;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsBooking;

class BookingForceDeleteTest extends DatabaseTestCase
{
    use TestsBooking;

    public function testDeletion()
    {
        $booking = $this->generateBooking();
        $this->generateBookingTraveller($booking);
        $this->runTestDeletion($booking);
    }

    private function runTestDeletion(Booking $booking)
    {
        $travellers = [];
        $id = $booking->id;
        foreach ($booking->travellers as $traveller) { $travellers[] = $traveller->id; }
        $booking->repository->forceDelete();
        $this->assertNull(Booking::find($id));
        foreach ($travellers as $tId) {
            $this->assertNull(BookingTraveller::find($tId));
        }
    }
}