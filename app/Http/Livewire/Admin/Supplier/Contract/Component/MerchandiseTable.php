<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Component;

use App\Models\Merchandise\MerchandiseInventory;
use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractComponent;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;

class MerchandiseTable extends ComponentTable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractComponent::query()
            ->where('component_type', '=', MerchandiseInventory::class)
            ->where('supplier_contract_id', '=', $this->contract->id)
            ->join('merchandise_inventories', 'supplier_contract_components.component_id', '=', 'merchandise_inventories.id')
            ->join('merchandises', 'merchandises.id', '=', 'merchandise_inventories.merchandise_id')
            ->join('variants', 'merchandise_inventories.variant_id', '=', 'variants.id')
            ->join('merchandise_sizes', 'merchandise_inventories.merchandise_size_id', '=', 'merchandise_sizes.id')
            ->join('merchandise_types', 'merchandises.merchandise_type_id', '=', 'merchandise_types.id');
    }

    public function columns()
    {
        return [
            Column::name('merchandises.name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Column::name('merchandise_types.name')
                ->label('Type')
                ->sortable()
                ->filterable(),
            Column::name('variants.name')
                ->label('Variant')
                ->sortable()
                ->filterable(),
            Column::name('merchandise_sizes.name')
                ->label('Size')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['cost_per_unit'], function ($price) {
                return f_currency($price);
            })
                ->label('Cost Per Unit')
                ->sortable()
                ->filterable(),
            NumberColumn::name('quantity')
                ->label('Quantity')
                ->sortable()
                ->filterable(),
            NumberColumn::callback(['cost_per_unit', 'quantity'], function ($cost, $quantity) { return f_currency($cost * $quantity); })
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
}