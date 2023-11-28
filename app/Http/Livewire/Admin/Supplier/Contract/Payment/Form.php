<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Payment;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractPayment;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    public SupplierContract|int $contract;
    public SupplierContractPayment|int|null $payment;

    public function mount(SupplierContract|int $contract, SupplierContractPayment|int|null $payment = null)
    {
        if (is_int($contract)) {
            $contract = SupplierContract::find($contract);
        }
        $this->contract = $contract;

        if (is_int($payment)) {
            $payment = SupplierContractPayment::find($payment);
        }
        if ($payment === null) {
            $payment = new SupplierContractPayment([
                'supplier_contract_id' => $this->contract->id,
                'exchange_rate' => $this->contract->agreed_exchange,
            ]);
        }
        $this->payment = $payment;
    }

    public function save()
    {
        $this->validate();
        $this->payment->supplier_contract_id = $this->contract->id;
        $this->payment->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.supplier.contract.payment.form');
    }

    public function rules()
    {
        return [
            'payment.paid' => 'nullable',
            'payment.amount' => 'required|numeric|gt:0',
            'payment.exchange_rate' => 'nullable|numeric|gt:0',
            'payment.notes' => 'nullable',
        ];
    }
}
