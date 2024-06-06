<?php

namespace App\Http\Livewire\Admin\Voucher;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Voucher\VoucherCode;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use LivewireForm;

    public $voucher;

    public function mount(VoucherCode|int|null $voucher)
    {
        if (is_int($voucher)) {
            $voucher = VoucherCode::find($voucher);
        }
        $this->voucher = $voucher ?? new VoucherCode();
        \Log::info($this->voucher);
    }

    public function save()
    {
        $this->validate();
        $this->voucher->global = $this->voucher->global ?? false;
        $this->voucher->limit = $this->voucher->limit ?? 0;
        $this->voucher->repository->save();
        $this->emit('refreshLivewireDatatable');
        $this->emit('closeModal');
    }
    public function render()
    {
        return view('livewire.admin.voucher.form');
    }

    public function rules()
    {
        return [
            'voucher.code' => 'required',
            'voucher.name' => 'required',
            'voucher.expiry' => 'required|date|date_format:Y-m-d',
            'voucher.description' => 'nullable',
            'voucher.limit' => 'nullable|integer',
            'voucher.active' => 'nullable|boolean',
            'voucher.global' => 'nullable|boolean',
        ];
    }
}
