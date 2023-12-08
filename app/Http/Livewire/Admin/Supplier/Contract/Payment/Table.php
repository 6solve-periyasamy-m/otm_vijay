<?php

namespace App\Http\Livewire\Admin\Supplier\Contract\Payment;

use App\Models\Supplier\SupplierContract;
use App\Models\Supplier\SupplierContractPayment;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class Table extends LivewireDatatable
{
    public SupplierContract $contract;

    public function builder()
    {
        return SupplierContractPayment::query()
            ->where('supplier_contract_id', '=', $this->contract->id)
            ->orderBy('paid');
    }

    public function columns()
    {
        return [
            DatetimeColumn::name('paid')
                ->label('Paid On')
                ->sortable(),
            NumberColumn::callback('amount', function ($amount) { return f_currency($amount); })
                ->label('Amount')
                ->sortable()
                ->searchable(),
            NumberColumn::callback('exchange_rate', function ($ex) { return rtrim($ex, '.0'); })
                ->label('Exchange Rate')
                ->sortable()
                ->searchable(),
            NumberColumn::callback(['amount', 'exchange_rate'], function ($amount, $ex) { return f_currency($amount * $ex, $this->contract->currency->code); })
                ->label('Exchanged Amount')
                ->sortable()
                ->searchable(),
            Column::name('notes')
                ->label('Notes')
                ->sortable()
                ->searchable(),
            Column::callback(['id', 'supplier_contract_id'],  function ($id, $contract) {
                return view('partials.admin.livewire.table.actions', [
                    'modal' => 'admin.supplier.contract.payment.form',
                    'field' => 'payment',
                    'id' => $id,
                    'parent' => "'contract': $contract",
                ]);
            })
                ->label(__('custom.table.actions')),
        ];
    }

    public function delete($id)
    {
        SupplierContractPayment::find($id)?->delete();
        $this->refreshLivewireDatatable();
    }
}