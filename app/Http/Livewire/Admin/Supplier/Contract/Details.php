<?php

namespace App\Http\Livewire\Admin\Supplier\Contract;

use App\Models\Supplier\SupplierContract;
use Livewire\Component;

class Details extends Component
{
    public SupplierContract $contract;

    public $listeners = ['refreshLivewireDatatable' => 'render',];

    public function render()
    {
        return view('livewire.admin.supplier.contract.details');
    }

    public function delete()
    {
        $supplier = $this->contract->supplier;
        $this->contract->repository->delete();
        return redirect()->route('supplier.view', ['supplier' => $supplier,]);
    }
}
