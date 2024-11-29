<?php

namespace App\Http\Livewire\Admin\Supplier\Contract;

use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierContract;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public $name = 'contract-table';

    public Supplier $supplier;

    public function builder()
    {
        return SupplierContract::query()
            ->join('currencies', 'currencies.id', '=', 'supplier_contracts.currency_id')
            ->where('supplier_id', '=', $this->supplier->id);
    }

    public function columns()
    {
        return [
            Column::callback(['purchase_order_number', 'id', 'supplier_id'], function ($name, $id, $supplier) {
                $route = route('supplier.contract', ['supplier' => $supplier, 'contract' => $id]);
                return "<a href=\"{$route}\">$name</a>";
            })
                ->label('Purchase Order Number')
                ->sortable()
                ->searchable(),
            BooleanColumn::name('confirmed')
                ->label('Confirmed')
                ->sortable()
                ->filterable(),
            Column::name('currencies.name')
                ->label('Currency')
                ->sortable()
                ->searchable(),
            NumberColumn::callback('agreed_exchange', function ($exchange) { return rtrim($exchange, "0"); })
                ->label('Agreed Exchange')
                ->sortable()
                ->filterable(),
            NumberColumn::callback('total_cost', function ($cost) { return f_currency($cost); })
                ->label('Total Cost')
                ->sortable()
                ->searchable()
                ->filterable(),
            Column::callback(['id', 'supplier_id'],  function ($id, $supplier) {
                return view('partials.admin.livewire.table.actions', [
                    'modal' => 'admin.supplier.contract.form',
                    'field' => 'contract',
                    'id' => $id,
                    'parent' => "'supplier': $supplier",
                ]);
            })
                ->label(__('custom.table.actions')),
        ];
    }

    public function delete($id)
    {
        $contract = SupplierContract::where('supplier_id', '=', $this->supplier->id)->find($id);
        $contract?->repository->delete();
        $this->refreshLivewireDatatable();
    }
}
