<?php

namespace App\Http\Livewire\Admin\Voucher\Tour;

use App\Http\Livewire\ShowsToast;
use App\Models\Tour\Tour;
use App\Models\Voucher\VoucherCode;
use Livewire\Component;

class Card extends Component
{
    use ShowsToast;

    public VoucherCode $voucher;
    public $tour;

    public function create()
    {
        $tour = Tour::find($this->tour);
        if (!isset($tour)) {
            $this->toastFromLang('voucher.tour.error.not-found', 'danger');
            return;
        }

    }

    public function render()
    {
        return view('livewire.admin.voucher.tour.card');
    }
}
