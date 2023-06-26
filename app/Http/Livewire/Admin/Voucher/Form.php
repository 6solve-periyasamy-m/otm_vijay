<?php

namespace App\Http\Livewire\Admin\Voucher;

use App\Models\Voucher\VoucherCode;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
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
            'voucher.active' => 'nullable|boolean',
            'voucher.global' => 'nullable|boolean',
        ];
    }
}
