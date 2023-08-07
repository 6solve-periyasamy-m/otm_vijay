<?php

namespace Field\Voucher\Booking;

use App\Models\Voucher\Executors\PercentageCostReductionExecutor;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsBooking;
use Tests\Traits\Model\TestsVoucher;

class BookingPercentageReductionTest extends DatabaseTestCase
{
    use TestsBooking;
    use TestsVoucher;

    public function testFlatReductionToTotal()
    {
        $booking = $this->generateBooking();
        $cost = $booking->repository->getTotalCost();
        $remaining = $booking->repository->getRemainingInstallmentAmount();
        $voucher = $this->generateVoucher();
        $voucher->results()->save(PercentageCostReductionExecutor::create(10));
        if (!$booking->repository->applyVoucher($voucher)) {
            $this->fail('Voucher Failed to Apply');
        }
        $this->assertEquals($cost - ($booking->tour->base_price_per_person * 0.1), $booking->repository->getTotalCost());
    }
}
