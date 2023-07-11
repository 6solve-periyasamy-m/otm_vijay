<?php

namespace App\Http\Livewire\Customer\Booking;

use App\Http\Livewire\SendsEvents;
use App\Models\Booking\BookingTraveller;
use App\Repository\Model\Voucher\VoucherCodeRepository;
use Livewire\Component;

class Voucher extends Component
{
    use SendsEvents;

    public BookingTraveller $traveller;
    public $code;

    public function apply()
    {
        $voucher = VoucherCodeRepository::find($this->code);
        if ($voucher?->repository->usable($this->traveller->booking->tour) ?? false) {
            $applied = $this->traveller->repository->applyVoucher($voucher);
            if (!$applied) {
                $this->toastFromLang('voucher.booking.messages.already-applied', 'danger', true);
                return;
            }
        } else {
            $this->toastFromLang('voucher.booking.messages.not-found', 'danger', true);
            return;
        }

        $this->toastFromLang('voucher.booking.messages.success', 'success', true);
        $this->emit('voucherChanged');
    }

    public function render()
    {
        return view('livewire.customer.booking.voucher');
    }
}
