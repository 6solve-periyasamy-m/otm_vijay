<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Models\Booking\BookingTraveller;
use App\Repository\Model\Voucher\VoucherCodeRepository;
use Livewire\Component;

class Voucher extends Component
{
    public BookingTraveller $traveller;
    public $code;

    public function apply()
    {
        $voucher = VoucherCodeRepository::find($this->code);
        if ($voucher?->repository->usable($this->traveller->booking->tour) ?? false) {
            $applied = $this->traveller->repository->applyVoucher($voucher);
            if (!$applied) {
                // Voucher code already applied to booking
            }
        } else {
            // Voucher code does not apply to booking
        }
        $this->emit('voucherChanged');
    }

    public function render()
    {
        return view('livewire.customer.booking.voucher');
    }
}
