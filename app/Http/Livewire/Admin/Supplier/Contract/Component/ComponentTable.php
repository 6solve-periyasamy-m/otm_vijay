<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\SupplierContractComponent;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

abstract class ComponentTable extends LivewireDatatable
{
    use SendsEvents;

    public function delete($id)
    {
        SupplierContractComponent::find($id)?->delete();
        $this->refreshTables();
    }
}