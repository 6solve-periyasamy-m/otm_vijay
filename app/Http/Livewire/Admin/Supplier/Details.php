<?php

namespace App\Http\Livewire\Admin\Supplier;

use App\Models\Supplier\Supplier;
use Livewire\Component;

class Details extends Component
{
    /** @var Supplier */
    public $supplier;
    protected $listeners = ['refreshLivewireDatatable' => 'render',];

    public function render()
    {
        return view('livewire.admin.supplier.details');
    }

    public function destroy()
    {
        $this->supplier->forceDelete();
        return redirect()->route('supplier.index');
    }
}
