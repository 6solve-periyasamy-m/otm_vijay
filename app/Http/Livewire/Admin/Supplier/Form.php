<?php

namespace App\Http\Livewire\Admin\Supplier;

use App\Http\Livewire\SendsEvents;
use App\Models\Helper\AddressParent;
use App\Models\Location\Address;
use App\Models\Supplier\Supplier;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /** @var Supplier */
    public $supplier;
    public Address $address;

    public function mount(Supplier|int|null $supplier): void
    {
        if (is_int($supplier)) {
            $supplier = Supplier::find($supplier);
        }
        if ($supplier === null) {
            $supplier = new Supplier();
        }
        $this->supplier = $supplier;
        $this->address = $this->supplier->address ?? new Address(['address_parent_id' => 1,]);
    }

    public function save()
    {
        $this->address->name = $this->supplier->name;
        $this->address->parent = $this->address->parent ?? AddressParent::SUPPLIER;
        $this->address->save();
        $this->supplier->address_id = $this->address->id;
        $this->supplier->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.supplier.form');
    }

    public function rules(): array
    {
        return [
            'supplier.name' => 'required',
            'supplier.email' => 'nullable',
            'supplier.website' => 'nullable',
            'supplier.telephone' => 'nullable',
            'supplier.currency_id' => 'nullable|integer|exists:currencies,id',
            'supplier.agreed_exchange' => 'nullable|numeric|gt:0',
            'address.address_line_1' => 'required',
            'address.address_line_2' => 'nullable',
            'address.town' => 'nullable',
            'address.region' => 'nullable',
            'address.country_id' => 'required|integer|exists:countries,id',
            'address.postcode' => 'nullable'
        ];
    }
}
