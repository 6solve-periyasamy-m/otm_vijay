<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\SupplierContractComponent;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /**
     * @noinspection PhpDocFieldTypeMismatchInspection
     * @var SupplierContractComponent Will always be component by the time it is used
     */
    public SupplierContractComponent|int $component;

    public function mount(SupplierContractComponent|int $component)
    {
        if (is_int($component)) {
            $component = SupplierContractComponent::find($component);
        }
        if ($component === null) {
            $this->closeModal();
            $this->refreshTables();
            $this->toast('Component Not Found', 'That component does not exist. Please refresh the page.', 'error');
            return;
        }
        $this->component = $component;
    }

    public function save()
    {
        $this->validate();
        $this->component->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.supplier.contract.component.form');
    }

    public function rules(): array
    {
        return [
            'component.quantity' => 'required|integer|gt:0',
            'component.cost_per_unit' => 'required|numeric|gte:0'
        ];
    }
}
