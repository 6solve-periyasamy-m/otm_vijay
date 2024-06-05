<?php

namespace App\Http\Livewire\Admin\Voucher\Result\Create;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Voucher\Executors\FlatCostReductionExecutor;
use App\Models\Voucher\VoucherCode;
use LivewireUI\Modal\ModalComponent;

class FlatAdjustment extends ModalComponent
{
    use SendsEvents;
    use LivewireForm;

    /** @var VoucherCode $voucher */
    public $voucher;
    public $amount;

    public function mount(VoucherCode|int $voucher)
    {
        if (is_int($voucher)) {
            $voucher = VoucherCode::find($voucher);
        }
        $this->voucher = $voucher;
    }

    public function submit()
    {
        if ($this->voucher instanceof VoucherCode) {
            $this->voucher->results()->save(FlatCostReductionExecutor::create($this->amount * -1));
            $this->toast(__('voucher.result.type.flat_reduction.toast.success.title'), __('voucher.result.type.flat_reduction.toast.success.body'), 'success');
        } else {
            $this->toast(__('voucher.result.type.flat_reduction.toast.failed.title'), __('voucher.result.type.flat_reduction.toast.failed.body'), 'danger');
        }
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.voucher.result.create.flat-adjustment');
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric',
        ];
    }
}
