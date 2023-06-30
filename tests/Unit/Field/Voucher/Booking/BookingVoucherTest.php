<?php

namespace Field\Voucher\Booking;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingTraveller;
use App\Models\Voucher\VoucherCode;
use Tests\DatabaseTestCase;
use Tests\Traits\TestsBooking;
use Tests\Traits\TestsVoucher;

class BookingVoucherTest extends DatabaseTestCase
{
    use TestsBooking;
    use TestsVoucher;

    public function testVoucherOnLeadInBookingVouchers()
    {
        $booking = $this->generateBooking();
        $voucher = $this->generateVoucher();
        $booking->repository->applyVoucher($voucher);
        $this->assertTrue($this->find($booking->leadTraveller, $voucher), 'Voucher not found on traveller');
        $this->assertTrue($this->find($booking, $voucher), 'Voucher not found on booking');
    }

    public function testVoucherOnAdditionalInBookingVoucher()
    {
        $booking = $this->generateBooking();
        $traveller = $this->generateBookingTraveller($booking);
        $voucher = $this->generateVoucher();
        $traveller->repository->applyVoucher($voucher);
        $this->assertTrue($this->find($traveller, $voucher), 'Voucher not found on traveller');
        $this->assertTrue($this->find($booking, $voucher), 'Voucher not found on booking');
    }

    private function find(Booking|BookingTraveller $haystack, VoucherCode $needle): bool
    {
        foreach ($haystack->vouchers as $voucher) {
            if ($voucher->id === $needle->id) return true;
        }
        return false;
    }
}
