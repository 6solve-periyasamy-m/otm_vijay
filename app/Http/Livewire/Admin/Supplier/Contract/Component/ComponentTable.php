<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Http\Livewire\SendsEvents;
use App\Models\Supplier\SupplierContractComponent;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

abstract class ComponentTable extends LivewireDatatable
{
    use SendsEvents;

    protected function componentColumns()
    {
        return [
            NumberColumn::callback(['cost_per_unit'], function ($price) {
                return f_currency($price, $this->getCurrencyCode());
            })
                ->label('Cost Per Unit')
                ->sortable()
                ->filterable(),
            NumberColumn::name('quantity')
                ->label('Quantity')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['cost_per_unit', 'quantity'], function ($cost, $quantity) { return f_currency($cost * $quantity, $this->getCurrencyCode()); })
                ->label('Total Cost')
                ->sortable()
                ->filterable(),
            Column::callback(['id'],  function ($id) {
                return view('partials.admin.livewire.table.actions', [
                    'modal' => 'admin.supplier.contract.component.form',
                    'field' => 'component',
                    'id' => $id,
                ]);
            })
                ->label(__('custom.table.actions')),
        ];
    }

    public function delete($id)
    {
        SupplierContractComponent::find($id)?->delete();
        $this->refreshTables();
    }

    protected abstract function getCurrencyCode(): string;
}