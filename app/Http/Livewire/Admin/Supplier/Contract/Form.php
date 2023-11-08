<?php /** @noinspection PhpDocFieldTypeMismatchInspection */

namespace App\Http\Livewire\Admin\Supplier\Contract;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierContract;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /** @var Supplier $supplier Will only be int at initialization */
    public Supplier|int $supplier;
    /** @var SupplierContract $contract */
    public SupplierContract|int|null $contract;

    public function mount(Supplier|int $supplier, SupplierContract|int|null $contract = null)
    {
        if (is_int($supplier)) {
            $supplier = Supplier::find($supplier);
        }
        $this->supplier = $supplier;

        if (is_int($contract)) {
            $contract = SupplierContract::find($contract);
        }
        if ($contract === null) {
            $contract = new SupplierContract([
                'currency_id' => $this->supplier->currency_id,
                'agreed_exchange' => $this->supplier->agreed_exchange,
            ]);
        }
        $this->contract = $contract;
    }

    public function save()
    {
        $this->validate();
        $this->contract->supplier_id = $this->contract->supplier_id ?? $this->supplier->id;
        $this->contract->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.supplier.contract.form');
    }

    public function rules()
    {
        return [
            'contract.purchase_order_number' => 'required',
            'contract.currency_id' => 'required|integer|exists:currencies,id',
            'contract.agreed_exchange' => 'required|numeric|gt:0',
            'contract.total_cost' => 'required|numeric',
            'contract.price_per_item' => 'nullable|numeric',
            'contract.confirmed' => 'required|boolean'
        ];
    }
}
