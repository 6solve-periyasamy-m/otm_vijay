<?php

namespace App\Http\Livewire\Admin\Supplier\Payment;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPaymentInformation;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    public Supplier|int $supplier;
    public SupplierPaymentInformation|int|null $information;

    public function mount(Supplier|int $supplier, SupplierPaymentInformation|int|null $information = null)
    {
        if (is_int($supplier)) {
            $supplier = Supplier::find($supplier);
        }
        $this->supplier = $supplier;
        if (is_int($information)) {
            $information = SupplierPaymentInformation::find($information);
        }
        if (is_null($information)) {
            $information = new SupplierPaymentInformation();
        }
        $this->information = $information;
    }

    public function save()
    {
        $this->validate();
        $this->information->supplier_id = $this->supplier->id;
        $this->information->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.supplier.payment.form');
    }

    public function rules()
    {
        return [
            'information.bank_id' => 'required|integer',
            'information.account_number' => 'required',
            'information.sort_code' => 'nullable',
            'information.bic_swift_code' => 'nullable',
            'information.iban' => 'nullable',
            'information.notes' => 'nullable',
        ];
    }
}
