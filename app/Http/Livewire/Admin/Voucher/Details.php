<?php

namespace App\Http\Livewire\Admin\Voucher;

use App\Models\Voucher\VoucherCode;
use Livewire\Component;

class Details extends Component
{
    public VoucherCode $voucher;

    public $listeners = ['refreshLivewireDatatable' => 'render', ];

    public function destroy()
    {
        $this->voucher->repository->delete();
        return redirect()->route('vouchers.index');
    }

    public function render()
    {
        return view('livewire.admin.voucher.details');
    }
}
