<?php

namespace App\Http\Livewire\Admin\Voucher\Result;

use App\Http\Livewire\ShowsToast;
use App\Models\Voucher\ResultType;
use App\Models\Voucher\VoucherCode;
use Livewire\Component;

class Card extends Component
{
    use ShowsToast;

    public $executor = 0;
    public VoucherCode $voucher;

    public function create()
    {
        $modal = match (ResultType::from($this->executor)) {
            ResultType::FLAT_ADJUSTMENT => 'admin.voucher.result.create.flat-adjustment',
            ResultType::PERCENTAGE_ADJUSTMENT => 'admin.voucher.result.create.percentage-adjustment',
            ResultType::FREE_COMPONENT => null,
        };
        if (!empty($modal)) {
            $this->emit('openModal', $modal, ['voucher' => $this->voucher->id,]);
        } else {
            $this->toast('Invalid Executor Type', 'Please select a valid executor type', 'danger');
        }

    }

    public function render()
    {
        return view('livewire.admin.voucher.result.card');
    }
}
