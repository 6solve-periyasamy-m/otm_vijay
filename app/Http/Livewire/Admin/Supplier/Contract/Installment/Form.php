<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Installment;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractInstallment;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;
    use LivewireForm;

    /** @var SupplierContract $contract */
    public mixed $contract;
    /** @var SupplierContractInstallment $installment */
    public mixed $installment;

    public function mount(SupplierContract|int $contract, SupplierContractInstallment|int|null $installment = null)
    {
        if (is_int($contract)) {
            $contract = SupplierContract::find($contract);
        }
        $this->contract = $contract;
        if (is_int($installment)) {
            $installment = SupplierContractInstallment::find($installment);
        }
        if (is_null($installment)) {
            $installment = new SupplierContractInstallment();
        }
        $this->installment = $installment;
    }

    public function save() {
        $this->validate();
        $this->installment->supplier_contract_id = $this->contract->id;
        $this->installment->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.supplier.contract.installment.form');
    }

    public function rules()
    {
        return [
            'installment.amount' => 'required|numeric|gt:0',
            'installment.due' => 'required'
        ];
    }
}
