<?php

namespace App\Http\Livewire\Admin\Supplier\Associate;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierAssociate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /**
     * @var SupplierAssociate
     */
    public $associate;
    public $supplier;

    public function mount(Supplier|int $supplier, SupplierAssociate|int|null $associate = null): void
    {
        $this->supplier = (is_int($supplier) ? $supplier : $supplier->id);
        $this->associate = SupplierAssociate::fetch($associate);
    }

    public function save()
    {
        $this->associate->supplier_id = $this->associate->supplier_id ?? $this->supplier;
        $this->associate->save();
        $this->refreshTables();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.supplier.associate.form');
    }

    public function rules(): array
    {
        return [
            'associate.name' => 'required',
            'associate.email' => 'nullable',
            'associate.primary_phone' => 'nullable',
            'associate.alternative_phone' => 'nullable',
            'associate.job_title' => 'nullable',
            'associate.notes' => 'nullable',
        ];
    }
}
