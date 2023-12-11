<?php /** @noinspection PhpDocFieldTypeMismatchInspection */

namespace App\Http\Livewire\Admin\Supplier\Contract;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class Form extends ModalComponent
{
    use SendsEvents;

    /** @var Supplier $supplier Will only be int at initialization */
    public Supplier|int $supplier;
    /** @var SupplierContract $contract */
    public SupplierContract|int|null $contract;
    public float $local_cost = 0;
    public float $before_tax = 0;

    public function mount(Supplier|int $supplier, SupplierContract|int|null $contract = null): void
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
        if (isset($this->contract->total_cost) && isset($this->contract->agreed_exchange)) {
            $this->local_cost = $this->contract->local_cost;
        }
        if (isset($this->contract->total_cost) && isset($this->contract->tax_rate)) {
            $this->before_tax = $this->contract->before_tax;
        }
    }

    public function save(): void
    {
        $this->validate();
        $this->contract->supplier_id = $this->contract->supplier_id ?? $this->supplier->id;
        $this->contract->save();
        $this->refreshTables();
        $this->closeModal();
    }

    public function changeLocal(): void
    {
        if (isset($this->contract->agreed_exchange)) {
            $this->contract->total_cost = sigfig($this->local_cost * $this->contract->agreed_exchange);
            $this->changeTax();
        }
    }

    public function changeGross(): void
    {
        if (isset($this->contract->agreed_exchange)) {
            $this->local_cost = $this->contract->local_cost;
        }
        $this->changeTax();
    }

    public function changeExchange(): void
    {
        if (isset($this->contract->total_cost)) {
            $this->local_cost = $this->contract->local_cost;
        } elseif (isset($this->local_cost)) {
            $this->contract->total_cost = sigfig($this->local_cost * $this->contract->agreed_exchange);
            $this->changeTax();
        }
    }

    public function changeTax(): void
    {
        $this->before_tax = $this->contract->before_tax;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.supplier.contract.form');
    }

    public function rules(): array
    {
        return [
            'contract.purchase_order_number' => 'required',
            'contract.reference_number' => 'nullable',
            'contract.currency_id' => 'required|integer|exists:currencies,id',
            'contract.agreed_exchange' => 'required|numeric|gt:0',
            'contract.tax_rate' => 'required|numeric|gte:0',
            'contract.total_cost' => 'required|numeric',
            'contract.confirmed' => 'nullable|boolean',
            'contract.notes' => 'nullable',
        ];
    }
}
