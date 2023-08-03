<?php

namespace Field\Voucher\Booking;

use App\Models\Voucher\Executors\FlatCostReductionExecutor;
use Tests\Bases\DatabaseTestCase;
use Tests\Traits\Model\TestsBooking;
use Tests\Traits\Model\TestsVoucher;

class BookingFlatReductionTest extends DatabaseTestCase
{
    use TestsBooking;
    use TestsVoucher;

    public function testFlatReductionToTotal()
    {
        $booking = $this->generateBooking();
        $cost = $booking->repository->getTotalCost();
        $remaining = $booking->repository->getRemainingInstallmentAmount();
        $voucher = $this->generateVoucher();
        $voucher->results()->save(FlatCostReductionExecutor::create(-100));
        if (!$booking->repository->applyVoucher($voucher)) {
            $this->fail('Voucher Failed to Apply');
        }
        $this->assertEquals($cost - 100, $booking->repository->getTotalCost());
        $this->assertEquals($remaining - 100, $booking->repository->getRemainingInstallmentAmount());
    }
}
