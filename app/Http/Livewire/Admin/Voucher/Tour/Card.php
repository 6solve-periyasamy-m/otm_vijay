<?php

namespace App\Http\Livewire\Admin\Voucher\Tour;

use App\Http\Livewire\SendsEvents;
use App\Models\Tour\Tour;
use App\Models\Voucher\VoucherCode;
use Livewire\Component;

class Card extends Component
{
    use SendsEvents;

    public VoucherCode $voucher;
    public $tour;

    protected function create($include = false)
    {
        $tour = Tour::find($this->tour);
        if (!isset($tour)) {
            $this->toastFromLang('voucher.tour.error.not-found', 'danger');
            return;
        }
        $include ? $this->voucher->include($tour) : $this->voucher->exclude($tour);
        $this->refreshTables();
    }

    public function include()
    {
        $this->create(true);
    }

    public function exclude()
    {
        $this->create(false);
    }

    public function render()
    {
        return view('livewire.admin.voucher.tour.card');
    }
}
